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

class HouseOwner  extends CI_Controller {
    public function getOwnerInfo($openId) {
        //思考下 这里还是需要把 信息拆分为3个部分 ownerinfo houseInfo parkInfo
        $res['ownerInfo'] = DB::row('houseOwner',['*'],['openId'=>$openId]);
        if($res['ownerInfo'] != null){
            $res['houseInfo'] = DB::row('houseInfo',['*'],['houseId'=>$res['ownerInfo']->houseId]);
            $res['parkInfo'] = DB::row('parkInfo',['*'],['parkId'=>$res['ownerInfo']->parkId]);
        }
        $this->json($res);
    }

    public function getHouseId(){
        $houseInfo = json_decode($_POST['houseInfo'],true);
        $res = DB::row('houseInfo',['houseId'],['building'=>$houseInfo['building'],'unit'=>houseInfo['unit'],'houseNum'=>houseInfo['houseNum']]);
        $this->json($res);
    }

    public function getParkId(){
        $houseInfo = json_decode($_POST['parkInfo'],true);
        $res = DB::row('parkInfo',['parkId'],['layer'=>$parkInfo['layer'],'parkNum'=>parkInfo['parkNum']]);
        $this->json($res);
    }

    public function storageOwner(){
        $ownerInfo = json_decode($_POST['ownerInfo'],true);
        //print_r($ownerInfo);
        DB::insert('houseOwner',$ownerInfo);
    }

    public function updateOwner(){
        $ownerInfo = json_decode($_POST['ownerInfo'],true);
        DB::update('houseOwner',$ownerInfo,['openId'=>$ownerInfo['openId']]);
    }

    /*
     *前端请求数据库添加图片
     *同步传入数据库
     * */
    public function update_db_img(){
        $open_id = $_POST['open_id'];
        $ksid = $_POST['ksid'];
        $imgUrl = $_POST['imgUrl'];
        $res = DB::update('kaoshengInfo',['photoUrl'=>$imgUrl],['open_id'=>$open_id,'ksid'=>$ksid]);
    }

    /*
     * 前端请求删除图片接口
     * 删除cos中的图片
     * 删除name格式 去掉前面的域名信息从 /filename/....开始
     * */
    public function deleteCosImg(){
        $img_name = $_POST['img_name'];
        $result = self::delete_object($img_name);
        $this->json($result);
    }

    //模块化，将删除对象的操作独立出来
    public static function delete_object($img_name){
        $cosClient = Cos::getInstance();
        $cosConfig = Conf::getCos();
        $result = $cosClient->deleteObject(array(
            'Bucket' =>'community',
            'Key' => $img_name));
        return $result;
    }

    public function getBuilding(){
        $sql = 'select distinct building from houseInfo';
        $temp = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach($temp as $t){
            array_push($res,$t['building']);
        }
        $this->json($res);
    }
    public function getUnit($building){
        $sql = "select distinct unit from houseInfo where building='$building'";
        $res = [];
        $temp = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC);
        foreach($temp as $t){
            array_push($res,$t['unit']);
        }
        $this->json($res);
    }
    public function getFloor($building,$unit){
        $sql = "select distinct floor from houseInfo where building='$building' AND unit='$unit'";
        $res = [];
        $temp = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC);
        foreach($temp as $t){
            array_push($res,$t['floor']);
        }
        $this->json($res);
    }
    public function getHouseNum($building,$unit,$floor){
        $sql = "select houseNum from houseInfo where building='$building' AND unit='$unit' AND floor='$floor'";
        $res = [];
        $temp = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC);
        foreach($temp as $t){
            array_push($res,$t['houseNum']);
        }
        $this->json($res);
    }

    public function isHouseOwner($openId){
        $res = DB::select('houseOwner',['*'],['openId'=>$openId]);
        if($res[0]->houseAuthState == "success"){
            $this->json(['isHouseOwner'=>true]);
        }else{
            $this->json(['isHouseOwner'=>false]);
        }
    }
}

