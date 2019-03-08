<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
defined('BASEPATH') OR exit('No direct script access allowed');
class Goods extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('goods_model');
        $this->load->helper('url_helper');
    }
    public function get_goods_json_by_class_id($class_id) {
        $data = $this->goods_model->get_goods_by_class_id($class_id);
        $this->json($data);
    }
    public function get_goods_info($goods_id) {
        $data = $this->goods_model->select_goods_by_goodsid($goods_id);
        $this->json($data[0]);
    }
    public function get_goods_list_info($json) {//json格式为"{"goods_list":[{"goods_id":"5","count":"1"},{"good…t":"4"},{"goods_id":"4","count":"2"}],"cost":873}"}"
        //$json = '{"goods_list":[{"goods_id":"5","count":"1"},{"goods_id":"4","count":"1"},{"goods_id":"1","count":"2"}],"cost":873}';
        $jstr = urldecode($json);
        $arr = json_decode($jstr,true);
        $data = [];
        for($i=0;$i < count($arr['goods_list']) ;$i=$i+1) {
            $info = $this->goods_model->select_goods_by_goodsid($arr['goods_list'][$i]['goods_id']);
            array_push($data,$info[0]);
        }
        $this->json($data);
    }
}
