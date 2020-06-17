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
use \QCloud_WeApp_SDK\Myapi\MyDate as MyDate;

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
        $isInDB = DB::select('houseOwner',['*'],['openId'=>$ownerInfo['openId']]);
        if($isInDB == null){
            DB::insert('houseOwner',$ownerInfo);
        }
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
        if($res != null && $res[0]->houseAuthState == "success"){
            $this->json(['isHouseOwner'=>true,'houseId'=>$res[0]->houseId]);
        }else{
            $this->json(['isHouseOwner'=>false]);
        }
    }

    public function getParkingCoupon(){
        //calc 是否欠费
        $openId = $_POST['openId'];
        $sql = "select houseOwner.*,parkInfo.* from houseOwner,parkInfo where houseOwner.parkId=parkInfo.parkId AND houseOwner.openId='$openId'";
        $result['ownerInfo'] = (DB::raw($sql))->fetchAll(PDO::FETCH_OBJ)[0];
        if($result['ownerInfo'] == null){//没有绑定车位，绑定车位只能靠物业的数据 这个没有办法的
            $this->json($result);
            return ;
        }
        //print_r($result['ownerInfo']);
        if($result['ownerInfo']->type == 'double'){
            $danjia = 80;
        }else if($result['ownerInfo']->type == 'single'){
            $danjia = 50;
        }
        $result['feeHist'] = DB::row('houseFeeHist',['*'],['houseId'=>$result['ownerInfo']->houseId,'type'=>'park']);
        if($result['feeHist'] != null){//有缴费记录
            $month = MyDate::diffDate($result['feeHist']->date,date("Y-m-d",time()))['month'];
            //print_r($result['feeHist']->fee - $danjia*$month);
            $result['isArrears'] = ($result['feeHist']->fee - $danjia*$month) > 0 ? false : true; //缴费 减去已经用去的 如果大于0 欠费为false 否则为true
            $result['parkCouponHist'] = DB::select('parkCoupon',['*'],['parkId'=>$result['ownerInfo']->parkId]);
        }
        $this->json($result);
    }
    public function generateCouponNum(){
        //生成4位数的唯一码0~9999够用了
        //1查询数据库 本月 所有已经用过的号码 生成4位随机数 不在result中 在用这个号码去数据库中确认一次 没有就insert and 返回给前段使用
    }
}

