<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends CI_Controller {

    /*
     * */
    public function get_msgs_by_class_id (){
        $class_id = $_POST['class_id'];
        $conditions = "class_id='$class_id' AND onoff='on' order by msg_id desc limit 10";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }

    /*
     * */
    public function get_msg_by_id (){
        $msg_id = $_POST['msg_id'];
        $msg = DB::row('community_msg',['*'],['msg_id'=>$msg_id]);
        $this->json($msg);
    }

    /*获取用户发布的消息列表
     * */
    public function get_user_messages($open_id){
        $conditions = "open_id='$open_id' AND onoff='on'";
        $res = DB::select('community_msg',['*'],$conditions);
        $this->json($res);
    }

    /*
     *
     * */
    public function lazy_load_msg() {
        $class_id = $_POST['class_id'];
        $min_id = $_POST['min_id'];
        $conditions = "class_id='$class_id' AND msg_id<$min_id AND onoff='on' order by msg_id desc limit 10";
        $msgs = DB::select('community_msg',['*'],$conditions);
        $this->json($msgs);
    }

    /*
     * */
    public static function store_message ($message){
        $message['pubtime'] =  date("Y-m-d H:i:s",time());
        $message['onoff'] =  'on';
        $result = DB::insert('community_msg',$message);
        return  $result;
    }

    /*发布锁
    * */
    public static function forbidden_publish($open_id){
        //$conditions = "open_id='$open_id' order by pubtime";
        //$res = DB::row('community_msg',['pubtime'],$conditions);
        //$seconds = time() - strtotime($res->pubtime);
        //if($seconds < 86400){//一天内已经有过发布,不再免费发布
            //return true;
        //}else{
            return false;
        //}
    }

    public function user_delete_msg($msg_id){
        $conditions = "msg_id='$msg_id'";
        $res = DB::update('community_msg',['onoff'=>'off'],$conditions);
    }

    public function user_publish_message(){
        //print_r($_POST['message']);
        $message = json_decode($_POST['message'],true);
        //var_dump($message);
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

    /*
     *前端请求删除图片
     涉及cos和DB必须同步
     update数据库url
     * */
    public function delete_db_img(){
        $msg_id = $_POST['msg_id'];
        $imgName = $_POST['img_name'];
        $msg = DB::row('community_msg',['images_name'],['msg_id'=>$msg_id]);
        //print_r($msg->images_name);
        if(isset($msg)){
            $nameList = explode(",",$msg->images_name);
            $index = array_search($imgName,$nameList);
            if($index >= 0){
                array_splice($nameList,$index,1);
                $msg->images_name = join(",",$nameList);
                $res = DB::update('community_msg',['images_name'=>$msg->images_name],['msg_id'=>$msg_id]);
                $this->json($res);
            }
        }
    }

    /*
     *前端请求数据库添加图片
     * */
    public function db_add_img(){
        $msg_id = $_POST['msg_id'];
        $imgName = $_POST['img_name'];
        $msg = DB::row('community_msg',['images_name'],['msg_id'=>$msg_id]);
        if(isset($msg)){
            $nameList = explode(",",$msg->images_name);
            array_push($nameList,$imgName);
            $msg->images_name = join(",",$nameList);
            $res = DB::update('community_msg',['images_name'=>$msg->images_name],['msg_id'=>$msg_id]);
            $this->json($res);
        }
    }

    /*
     * 删除cos中的图片
     * */
    public function delete_cos_img(){
        $img_name = $_POST['img_name'];
        $cosClient = Cos::getInstance();
        $cosConfig = Conf::getCos();
        $result = $cosClient->deleteObject(array(
            'Bucket' =>'community',
            'Key' => $img_name));
        $this->json($result->toArray());
    }

    public function update_message(){
        $message = json_decode($_POST['message'],true);
        $result = DB::update('community_msg',$message,['msg_id'=>$message['msg_id']]);
        return  $result;
    }
}
