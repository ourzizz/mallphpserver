<?PHP
//本文件为前端提供商品的信息
//为了适应多个模块复用 评论的功能 后台给定的 api需要区别是关于那个tablename
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
defined('BASEPATH') OR exit('No direct script access allowed');
class MyCos extends CI_Controller {
    public function getImageNameList(){
        $tableName=$_POST['tableName'];
        $uid =$_POST['uid'];
        $res = [];
        $temp = DB::select('imgs_buket',['url'],['tableName'=>$tableName,'type_id'=>$uid]);
        foreach($temp as $t){
            array_push($res,$t->url);
        }
        $this->json($res);
    }

    public function DbAddImg(){//插入一张图片
        $insertValue['tableName']= $_POST['tableName'];
        $insertValue['type_id']= $_POST['uid'];
        $insertValue['url']= $_POST['imgName'];
        DB::insert('imgs_buket',$insertValue);
        //$res = DB::row('imgs_buket',$insertValue)->uid;
        //$this->json($res);
    }

    public function deleteCosImg(){
        $imgName = $_POST['imgName'];
        $result = self::delete_object($imgName);
    }

    public function DbDeleteImg(){
        $imgName = $_POST['imgName'];
        DB::delete('imgs_buket',['url'=>$imgName]);
    }
    public static function delete_object($imgName){
        $cosClient = Cos::getInstance();
        $cosConfig = Conf::getCos();
        $result = $cosClient->deleteObject(array(
            'Bucket' =>'community',
            'Key' => $imgName));
        return $result;
    }
    public function dbStoragePhotos(){
        $tableName = $_POST['tableName'];
        $photoList = json_decode($_POST['photoList'],true);
        $uid = $_POST['uid'];
        foreach($photoList as $photo){
            DB::insert('imgs_buket',['url'=>$photo,'tableName'=>$tableName,'type_id'=>$uid]);
        }
    }
}
