<?PHP
//本文件为前端提供商品的信息
defined('BASEPATH') OR exit('No direct script access allowed');
class  Area extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->helper('url_helper');
    }

    public function get_sons_list_by_area_id($pid) {
        $data = $this->area_model->select_sons_by_pid($pid);
        $this->json($data);
    }

    //public function get_area_json($pid) {
        ////递归调用栈太深，无法执行，为快速上线，抛弃该做法
        ////直接分段请求
        //$provincies =  $this->area_model->select_province();
        //$this->json($provincies);
        ////for($i = 0;$i < count($provincies);$i++) {
        //for($i = 0;$i < 1;$i++) {
            //$data = [];
            //$pid = $provincies[$i]['ID'];
            //$this->area_model->g_json($data,$pid);
            //$this->json($data);
        //}
        //echo "ok";
    //}
}
