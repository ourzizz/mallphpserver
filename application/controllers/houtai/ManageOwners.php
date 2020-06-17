<?PHP
//本文件，为商品分类提供基础操作
//这些后台操作功能都没有做验证，下一步需要完善验证操作
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;
defined('BASEPATH') OR exit('No direct script access allowed');

class ManageOwners extends CI_Controller{
    public function getManagerRole($openId){
        $res = DB::row('seller',['*'],['open_id'=>$openId]);
        if($res == null){
            $res['role'] = 'nobody';
        }
        $this->json($res);
    }
    public function getWaitAuthList(){
        $res = DB::select('houseOwner',['houseId','name','openId','houseAuthState'],['houseAuthState'=>'wait']);
        $this->json($res);
    }
    public function getOwnerDetail($openId){
        $res = DB::select('houseOwner',['*'],['openId'=>$openId]);
        $this->json($res);
    }
    public function shenhePass(){
        $openId = $_POST['openId'];
        DB::update('houseOwner',['houseAuthState'=>'success'],['openId'=>$openId]);
    }
    public function shenheRefuse(){
        $openId = $_POST['openId'];
        $reason = $_POST['reason'];
        DB::update('houseOwner',['houseAuthState'=>'refuse','houseReason'=>$reason],['openId'=>$openId]);
    }
    public function updateOwner($openId){//审核结果直接update过来简化操作
    }
    public function getOwnerByHouseId($houseId){//可能存在多个一户多业主的情况
        $res = DB::select('houseOwner',['*'],['houseId'=>$houseId]);
        $this->json($res);
    }
}
