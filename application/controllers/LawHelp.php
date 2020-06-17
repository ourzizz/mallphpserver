<?PHP
//本文件为前端提供商品的信息
//为了适应多个模块复用 评论的功能 后台给定的 api需要区别是关于那个tablename
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Myapi\Mingan as MG;
defined('BASEPATH') OR exit('No direct script access allowed');
class LawHelp extends CI_Controller {
    public function lazyLoadRepair() {
        $class_id = $_POST['class_id'];
        $min_id = $_POST['min_id'];
        $conditions = "repairId<$repairId order by repairId desc limit 5";
        $repairList = DB::select('repair',['*'],$conditions);
        $this->json($repairList);
    }

    public function getRepairList(){
        $repairList = DB::select('repair',['*']);
        $this->json($repairList);
    }

    public function getRepairDetail($repairId){
        $res['repair'] = DB::row('repair',['*'],['repairId'=>$repairId]);
        $res['photoList'] = [];
        $temp = DB::select('imgs_buket',['url'],['tableName'=>'repair','type_id'=>$repairId]);
        foreach($temp as $t){
            array_push($res['photoList'],$t->url);
        }

        $res['repairProcess'] = DB::select('repairProcess',['*'],['repairId'=>$repairId]);
        foreach($res['repairProcess'] as $process){
            $photos = DB::select('imgs_buket',['url'],['tableName'=>'repairProcess','type_id'=>$process->repairProcessId]);
            $process->photoList = [];
            foreach($photos as $photo){
                array_push($process->photoList,$photo->url);
            }
        }
        $this->json($res);
    }

    /*获取用户发布的消息列表
     * */
    public function getUserRepairList($openId){
        $conditions = "openId='$openId'";
        $res = DB::select('repair',['*'],$conditions);
        $this->json($res);
    }

    /*对cos进行第一道删除再删掉本条消息
     * */
    public function userDeleteRepair ($repairId){
        //$msg = DB::row('community_msg',['images_name'],['msg_id'=>$msg_id]);
        //$nameList = explode(",",$msg->images_name);
        //foreach($nameList as $img_name){
            //if($img_name !== ''){
                //self::delete_object($img_name);
            //}
        //}
        //$conditions = "msg_id='$msg_id'";
        //$res = DB::delete('community_msg',$conditions);
    }

    public function userPublishRepair(){
        $repair = json_decode($_POST['repair'],true);
        $photoList = json_decode($_POST['photoList'],true);
        $ismingan = MG::check_words($repair['content'].$repair['theme']);
        $repair['pubTime'] =  date("Y-m-d H:i:s",time());
        if($ismingan['errcode'] == 87014){//是否通过敏感测试
            $this->json(['mingan'=>true]);//敏感
        }else{
            DB::insert('repair',$repair);
            $repairId = (DB::row('repair',['repairId'],['content'=>$repair['content'],'pubTime'=>$repair['pubTime']]))->repairId;
            $this->json( ['mingan'=>false,'repairId'=>$repairId]);
        }
    }

    public static function storageRepair ($repair){
    }

    /*
     *前端请求删除图片
     涉及cos和DB必须同步
     update数据库url
     * */
    public function delete_db_img(){
        $img_name = $_POST['img_name'];
        $msg = DB::delete('imgs_buket',['url'=>$img_name]);
    }

    /*
     *前端请求数据库添加图片
     *同步传入数据库
     * */
    public function db_add_img(){
        $imgName = $_POST['img_name'];
        $repairId = $_POST['repairId'];
        DB::insert('imgs_buket',['url'=>$imgName,'tableName'=>'repair','type_id'=>$repairId]);
    }

    /*
     * 前端请求删除图片接口
     * 删除cos中的图片
     * */
    public function delete_cos_img(){
        $img_name = $_POST['img_name'];
        $result = self::delete_object($img_name);
    }

    /*
     * 前端修改消息完毕提交的时候需要更新DB
     */
    public function updateRepair(){
        $repair = json_decode($_POST['repair'],true);
        $result = DB::update('repair',$repair,['repairId'=>$repair['repairId']]);
        return  $result;
    }

    //模块化，将删除对象的操作独立出来
    public static function delete_object($img_name){
        $cosClient = Cos::getInstance();
        $cosConfig = Conf::getCos();
        $result = $cosClient->deleteObject(array(
            'Bucket' =>'community',
            'Key' => $img_name));
        return $result;
    }
    
    //public function userDeleteRepair($rep)

}
