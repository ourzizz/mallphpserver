<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
defined('BASEPATH') OR exit('No direct script access allowed');
class Community extends CI_Controller {
    /*
     * */
    public function get_msgs_by_class_id (){
        $class_id = $_POST['class_id'];
        $conditions = "class_id='$class_id' order by pubtime";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }

    /*
     * 获取社区公告消息列表
     * */
    public function get_head_msgs (){
        $conditions = "class_id=255";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }
}
