<?php
//小区业主API接口
//涉及到4个表 house park houseOwner photos
//
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Myapi\Mingan as MG;

class HouseInfo  extends CI_Controller {
    public function getBuilding(){
        $res = DB::select('houseInfo',['building']);
        $this->json($res);
    }
}

