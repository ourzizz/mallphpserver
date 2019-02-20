<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;

class Test_tunnel extends CI_Controller {
    public function index(){
        $connectedTunnelIds = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        print_r(array($connectedTunnelIds));
    }
}
