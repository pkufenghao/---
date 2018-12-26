<?php
//获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'fenghao';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }else{

        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
       $postObj = simplexml_load_string( $postArr );
      
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
               $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
   			 }
        }if(strtolower( $postObj->MsgType) == 'text'){
			//获得发送数据
        	 $content= $postObj->Content; 
			 //后两个字符
        	 $str = mb_substr($content,-2,2,"UTF-8");
			 //城市名称
			$str_key = mb_substr($content,0,-2,"UTF-8");
			//判断是否查询天气
         if($str == '天气' && !empty($str_key))
			{
    		if(!empty($str_key)){
				//请求数据
       			 $json=file_get_contents("http://139.199.127.163/city/".$str_key);
				 //json解码
       		    $data= json_decode( $json );
    		} else {
        		return null;
    				}
			}
               $toUser   = $postObj->FromUserName;
               $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $contentStr = "【".$data->cityInfo->city."天气预报】\n".
                  			   $data->data->forecast[0]->ymd." ".$data->cityInfo->updateTime."更新"."\n\n".
                  			  "今天".$data->data->forecast[0]->week."\n".                  			 
                  			  "天气：".$data->data->forecast[0]->type."\n"
                             ."温度：".$data->data->wendu."℃"."\n".
                  			  "湿度：".$data->data->shidu."\n".
                   			"风力：".$data->data->forecast[0]->fx.$data->data->forecast[0]->fl."\n".
                  			  "PM2.5：".$data->data->pm25."\n".
                  			  "空气质量：".$data->data->quality."\n".
                  			  "温馨提示：".$data->data->ganmao.
                  			"\n\n明天".$data->data->forecast[1]->week."\n".
                   				"天气：".$data->data->forecast[1]->type."\n"
                             ."温度：".mb_substr($data->data->forecast[1]->low,6).
							 "~".mb_substr($data->data->forecast[1]->high,6)."\n".         
                 			 "风力：".$data->data->forecast[1]->fx.$data->data->forecast[1]->fl."\n".
                  			"\n后天".$data->data->forecast[2]->week."\n".
                   			"天气：".$data->data->forecast[2]->type."\n"
                             ."温度：".mb_substr($data->data->forecast[2]->low,6).
							 "~".mb_substr($data->data->forecast[2]->high,6)."\n".
                  			  "风力：".$data->data->forecast[2]->fx.$data->data->forecast[2]->fl;
                  				 
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $contentStr);
                echo $info;
        }

      

    }
?>