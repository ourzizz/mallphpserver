<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');

//use \QCloud_WeApp_SDK\WxPay as PAY;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;

require APPPATH.'business/WxPay.php';
class Test_tunnel extends CI_Controller {
    public function index(){
        WxPay::test_static();
        //Pay::test_static();
    }
}
