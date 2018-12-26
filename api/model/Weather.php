<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class Weather extends Model
{
    public function getWeather($id)
    {
        $res = Db::name('ins_county')->where('weather_code', $id)->column('weather_info');
        return $res;
    }
  public function getCityCode($city = 1){
		$res = Db::name('ins_county')->where('county_name',$city)->column('weather_code');
  		return $res;
}
}
?>