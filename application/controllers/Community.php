<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends CI_Controller {
    /*
     * */
    public function get_msgs_by_class_id (){
        $class_id = $_POST['class_id'];
        $conditions = "class_id='$class_id' order by msg_id desc limit 10";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }

    /*
     *
     * */
    public function lazy_load_msg() {
        $class_id = $_POST['class_id'];
        $min_id = $_POST['min_id'];
        $conditions = "class_id='$class_id' AND msg_id<$min_id order by msg_id desc limit 10";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }

    /*
     * */
    public static function store_message ($message){
        $message['pubtime'] =  date("Y-m-d H:i:s",time());
        $result = DB::insert('community_msg',$message);
        return  $result;
    }

    /*
    * */
    public static function forbidden_publish($open_id){
        $conditions = "open_id='$open_id' order by pubtime";
        $res = DB::row('community_msg',['pubtime'],$conditions);
        $seconds = time() - strtotime($res->pubtime);
        if($seconds < 86400){//一天内已经有过发布,不再免费发布
            return true;
        }else{
            return false;
        }
    }

    public function user_publish_message(){
        $message = json_decode($_POST['message'],true);
        $result = self::store_message($message);
        //$this->json(['result'=>$result]);
        if(isset($message) && !(self::forbidden_publish($message['open_id']))){
            $result = self::store_message($message);
            $this->json(['result'=>$result]);
        }else{
            $this->json(['result'=>'forbidden']);
        }
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
