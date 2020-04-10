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
        $res['disComment'] = DB::select('discussComment',['*'],['discussId'=>$discussId]);
        $res['discussOptions'] = DB::select('discussOptions',['*'],['discussId'=>$discussId]);
        $res['userVoteHist'] = DB::row('discussVoteHist',['*'],['openId'=>$openId,'discussId'=>$discussId]);
        $this->json($res);
    }
    public function userVote(){
        $openId = $_POST['openId'];
        $discussId = $_POST['discussId'];
        $discussOptionsId = $_POST['discussOptionsId'];
        $voteTime =  date("Y-m-d H:i:s",time());
        //保存投票记录
        DB::insert('discussVoteHist',['openId'=>$openId,'discussId'=>$discussId,'discussOptionsId'=>$discussOptionsId,'date'=>$voteTime]);
        //选项得票+1
        $sql = "update discussOptions set getVotes=getVotes+1 where discussOptionsId='$discussOptionsId'";
        DB::raw($sql);//
    }
}
