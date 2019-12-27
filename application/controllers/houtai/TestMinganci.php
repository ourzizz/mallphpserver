<?PHP
use \QCloud_WeApp_SDK\Helper\Request as Request;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\WxPay\WxPay as WP;
use \QCloud_WeApp_SDK\Myapi\Mingan as MG;
defined('BASEPATH') OR exit('No direct script access allowed');

class TestMinganci extends CI_Controller{
    public function  test(){
        $content = $_POST['content'];
        MG:: check_words($content);
    }
    public function imgtest(){
        $res = MG:: check_img();
        $this->json($res);
    }
}
