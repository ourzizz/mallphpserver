<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
defined('BASEPATH') OR exit('No direct script access allowed');
class ManageDiscuss extends CI_Controller {
    public function getDiscussList(){
        $res = DB::select('discuss',['discussId','theme','pubDate']);
        $this->json($res);
    }

    public function getDiscussDetail($discussId){
        $res['discuss'] = DB::row('discuss',['*'],['discussId'=>$discussId]);
        $res['discussOptions'] = DB::select('discussOptions',['*'],['discussId'=>$discussId]);
        $this->json($res);
    }

    public function updateDiscuss(){
        //$discuss = json_decode($_POST['discuss'],true);
        //DB::update('discuss',$discuss,['discussId'=>$discuss['discussId']]);

        $discussId = $_POST['discussId'];
        $state = $_POST['state'];
        DB::update('discuss',['state'=>$state],['discussId'=>$discussId]);
    }

    //public function passDiscuss(){}
    //public function refuseDiscuss(){}
}
