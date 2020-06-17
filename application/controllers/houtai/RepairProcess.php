<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Myapi\Mingan as MG;
use \QCloud_WeApp_SDK\Myapi\MyDate as MyDate;

class RepairProcess  extends CI_Controller {
    public function storageProcess(){
        $process = json_decode($_POST['process'],true);
        $process['pubTime'] =  date("Y-m-d H:i:s",time());
        DB::insert('repairProcess',$process);
        $res['repairProcessId'] = DB::row('repairProcess',['repairProcessId'],['pubTime'=>$process['pubTime'],'detail'=>$process['detail']])->repairProcessId;
        $this->json($res);
    }

    public function updateProcess(){
        $process = json_decode($_POST['process'],true);
        $process['pubTime'] =  date("Y-m-d H:i:s",time());
        DB::update('repairProcess',['detail'=>$process['detail']],['repairProcessId'=>$process['repairProcessId']]);
    }

    public function deleteProcess(){
        $repairProcessId = $_POST['repairProcessId'];
        DB::delete('repairProcess',['repairProcessId'=>$repairProcessId]);
        $imgNames = DB::select('imgs_buket',['*'],['tableName'=>'repairProcess','type_id'=>$repairProcessId]);
        foreach($imgNames as $name){
            self::delete_object($name->url);
        }
        DB::delete('imgs_buket',['tableName'=>'repairProcess','type_id'=>$repairProcessId]);
    }

    public static function delete_object($imgName){
        $cosClient = Cos::getInstance();
        $cosConfig = Conf::getCos();
        $result = $cosClient->deleteObject(array(
            'Bucket' =>'community',
            'Key' => $imgName));
        return $result;
    }
}

