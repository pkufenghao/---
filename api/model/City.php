<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class City extends Model
{
    public function getWeather($id)
    {
      $res = Db::name('ins_county')->where('county_name', $id)->column('weather_info');
       // $res = Db::name('ins_county')->where('county_name', $id)->value('weather_info');
        return $res;
    }
}
?>