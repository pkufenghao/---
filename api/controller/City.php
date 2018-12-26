<?php
namespace app\api\controller;
use think\Controller;
class City extends Controller
{
  public function read(){
    $id=input('id');
    $model=model('City');
    $data =$model->getWeather($id);
    $html =json_decode($data[0]);
    return json($html);
  } 
  }
  ?>