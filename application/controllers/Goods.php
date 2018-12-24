<?PHP
//本文件为前端提供商品的信息
defined('BASEPATH') OR exit('No direct script access allowed');
class Goods extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('goods_model');
        $this->load->helper('url_helper');
    }
    public function get_goods_json_by_class_id($class_id)
    {
        $data = $this->goods_model->get_goods_by_class_id($class_id);
        $this->json($data);
    }
}
