
<?PHP
//本文件为前端提供商品的信息
defined('BASEPATH') OR exit('No direct script access allowed');
class Shopcart extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('shopcart_model');
        $this->load->helper('url_helper');
    }
    public function user_add_goods($open_id,$goods_id) {
        $data = $this->shopcart_model->insert_user_chose_goods($open_id,$goods_id);
        $this->json('成功');
    }
    public function get_user_has_goods($open_id) {
        $data = $this->shopcart_model->select_user_has_goods($open_id);
        $this->json($data);
    }
    public function add_count($open_id,$goods_id) {
    }
    public function reduce_count($open_id,$goods_id) {
    }
}
