
<?PHP
//本文件，为商品分类提供基础操作
//这些后台操作功能都没有做验证，下一步需要完善验证操作
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;
defined('BASEPATH') OR exit('No direct script access allowed');

class ManageAdm extends CI_Controller{
    public function getAdmInfo($openId){
        $res = DB::row('seller',['*'],['open_id'=>$openId]);
        if($res == null){
            $res['role'] = 'nobody';
        }
        $this->json($res);
    }

    public function storageAdmInfo(){
        $admInfo = json_decode($_POST['admInfo'],true);
        $res = DB::select('seller',['*'],['open_id'=>$admInfo['open_id']]);
        if($res == null){
            DB::insert('seller',$admInfo);
        }
    }

    public function updateAdmInfo(){
        $admInfo = json_decode($_POST['admInfo'],true);
        DB::update('seller',$admInfo,['open_id'=>$admInfo['open_id']]);
    }

}
