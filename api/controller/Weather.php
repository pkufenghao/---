<?php
namespace app\api\controller;
use think\Controller;
class Weather extends Controller
{
  public function read()
  {
  	$id = input('id');
    $model = model('Weather');
    $data  = $model->getWeather($id);
    $html=json_decode($data[0]);
    return json($html);
  }
  
  }
  ?>