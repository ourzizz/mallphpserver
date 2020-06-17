<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
defined('BASEPATH') OR exit('No direct script access allowed');
class Discuss extends CI_Controller {
    //给出前五条文件
    public function loadDiscuss($discussId){
        $conditions = "discussId>$discussId AND state='pass' order by discussId desc limit 15";
        $res = DB::select('discuss',['discussId','theme','pubDate'],$conditions);
        $this->json($res);
    }

    public function getDiscussDetail($discussId,$openId){
        $res['discuss'] = DB::row('discuss',['*'],['discussId'=>$discussId]);
        $res['discussOptions'] = DB::select('discussOptions',['*'],['discussId'=>$discussId]);
        $res['userVoteHist'] = DB::row('discussVoteHist',['*'],['openId'=>$openId,'discussId'=>$discussId,'state'=>'valid']);
        $this->json($res);
    }
    public function userVote(){
        $openId = $_POST['openId'];
        $discussId = $_POST['discussId'];
        $discussOptionsId = $_POST['discussOptionsId'];
        $voteTime =  date("Y-m-d H:i:s",time());
        //保存投票记录
        DB::insert('discussVoteHist',['openId'=>$openId,'discussId'=>$discussId,'discussOptionsId'=>$discussOptionsId,'date'=>$voteTime,'state'=>'valid']);
        //选项得票+1
        $sql = "update discussOptions set getVotes=getVotes+1 where discussOptionsId='$discussOptionsId'";
        DB::raw($sql);//
        $res['userVoteHist'] = DB::row('discussVoteHist',['*'],['openId'=>$openId,'discussId'=>$discussId,'discussOptionsId'=>$discussOptionsId,'date'=>$voteTime,'state'=>'valid']);
        $this->json($res);
    }
    public function userCancleVote(){
        $voteId = $_POST['voteId'];
        $voteTime =  date("Y-m-d H:i:s",time());
        $hist = DB::row('discussVoteHist',['*'],['voteId'=>$voteId]);
        DB::update('discussVoteHist',['state'=>'invalid','date'=>$voteTime],['voteid'=>$voteId]);
        $discussOptionsId = $hist->discussOptionsId;
        //选项得票-1
        $sql = "update discussOptions set getVotes=getVotes-1 where discussOptionsId='$discussOptionsId'";
        DB::raw($sql);//
    }

    public function storage(){
        $discuss = json_decode($_POST['discuss'],true);
        $options = json_decode($_POST['options'],true);
        $discuss['pubDate'] =  date("Y-m-d H:i:s",time());
        DB::insert('discuss',$discuss);
        $discussId = DB::row('discuss',['discussId'],['pubDate'=>$discuss['pubDate'],'content'=>$discuss['content']])->discussId;
        foreach($options as $option){
            $option['discussId'] = $discussId;
            DB::insert('discussOptions',$option);
        }
    }

    public function update(){
        $discuss = json_decode($_POST['discuss'],true);
        $options = json_decode($_POST['options'],true);
        DB::update('discuss',$discuss,['discussId'=>$discuss['discussId']]);
        $discussId = $discuss['discussId'];
        foreach($options as $option){
            print($option['discussOptionsId']);
            if($option['discussOptionsId'] == 'new'){//新增
                DB::insert('discussOptions',$option);
            }else{
                DB::update('discussOptions',$option,['discussOptionsId'=>$option['discussOptionsId']]);
            }
        }
    }

    public function getUserDiscussList($openId){
        $res = DB::select('discuss',['discussId','theme','pubDate'],['openId'=>$openId]);
        $this->json($res);
    }

    public function removeOption(){
        $discussOptionsId = $_POST['discussOptionsId'];
        DB::delete('discussOptions',['discussOptionsId'=>$discussOptionsId]);
    }
}
