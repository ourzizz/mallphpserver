<?PHP
//本文件为前端提供商品的信息
defined('BASEPATH') OR exit('No direct script access allowed');
class User_address extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_address_model');
        $this->load->helper('url_helper');
    }
    public function get_user_address($open_id) {
        $data = $this->goods_model->select_user_address($open_id);
        $this->json($data);
    }
}
