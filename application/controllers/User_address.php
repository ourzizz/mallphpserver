<?PHP
//本文件为前端提供商品的信息
defined('BASEPATH') OR exit('No direct script access allowed');
class User_address extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_address_model');
        $this->load->helper('url_helper');
    }

    public function index() {
        echo "ok";
    }

    public function set_default_address($open_id,$address_id){
        $data = $this->user_address_model->update_default_address($open_id,$address_id);
    }

    public function user_delete_address($open_id,$address_id){
        $data = $this->user_address_model->delete_address($open_id,$address_id);
    }

    public function get_user_default_address($open_id) {
        $data = $this->user_address_model->select_user_default_address($open_id);
        $this->json($data);
    }

    public function get_user_all_address($open_id) {
        $data = $this->user_address_model->select_user_all_address($open_id);
        $this->json($data);
    }

    public function user_add_address($open_id,$address) {//insert成功后应该返回address_id 给前台,
        $jstr = urldecode($address);
        $address = json_decode($jstr,true);
        $data = $this->user_address_model->insert_user_address($open_id,$address);
    }

    public function user_update_address($open_id,$address_id,$address_json){//name:1,telphone2,area3,detail:4
       //数据格式[{"key":1,"value":"chenhai"},{"key":2,"value":"123123123"},{"key":3,"value":["湖北省","黄石市","黄石港区"]},{"key":4,"value":"xxxxx"}] 
        $address = json_decode(urldecode($address_json),true);
        foreach($address as $a) {
                $this->user_address_model->update_address($open_id,$address_id,$a);
        }
    }
}
