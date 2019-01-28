<?PHP
use QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Helper\Util as Util;
class Test_http extends CI_Controller {
    public function index(){
        //$code = Util::getHttpHeader(Constants::WX_HEADER_CODE);
        //$headerKey = strtoupper('head');
        //$headerKey = str_replace('-', '_', $headerKey);
        //$headerKey = 'HTTP_' . $headerKey;
        $data = json_decode($_POST['goods_list']);
        $headerKey = Util::getHttpHeader('order_list');
        print_r($data);
        //echo $data[0]->open_id;
    }
}
