<?PHP
defined('BASEPATH') OR exit('No direct script access allowed');
class Demo extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('file_model');
        $this->load->model('user_model');
        $this->load->model('eventtime_model');
        $this->load->helper('url_helper');
    }

    public function get_file_list($typeid = "1") {
        //$typeid = urldecode($typeid);
        $data['files'] = $this->file_model->get_files_title($typeid);
            $this->json(
                $data
            );
    }

    public function index() {
        $data['files'] = $this->file_model->get_files_title();
        $this->load->view('templates/header',$data);
        $this->load->view('article/filelist',$data);
        $this->load->view('templates/footer');
    }

    public function show_article($fileid=FALSE)
    {
        if ($fileid===FALSE) {
            show_404();
        }
        $data['article'] = $this->file_model->get_article($fileid);
        $this->load->view('templates/header',$data);
        $this->load->view('article/view',$data);
        $this->load->view('templates/footer');
    }


    public function get_kstype() {
        $data['type'] = $this->file_model->get_kstype();
            $this->json(
                $data
            );
    }

    public function get_headline() 
    {
        $data['type'] = $this->file_model->get_headline();
            $this->json(
                $data
            );
    }
    public function get_homepage_json() {
        //首页包含头条，4个考试大类，展现指导文件列表
        $data['type'] = $this->file_model->get_kstype();
        $data['headline'] = $this->file_model->get_headline();
        $data['guidelist'] = $this->file_model->get_guide_list();
        $data['newfiles'] = $this->file_model->get_new_files();
            $this->json(
                $data
            );
    }
    public function get_file_msg($typeid) 
    {//获取某个类型考试的文件和msg
        $data['msg'] = $this->file_model->get_msg($typeid);
        $data['files'] = $this->file_model->get_files_title($typeid);
            $this->json(
                $data
            );
    }
    public function get_zhuanji_list() 
    {
        $data['zhuanjilist'] = $this->file_model->get_zhuanji_list();
        $this->json(
            $data
        );
    }
    public function get_zhuanji_file_msg($typeid) 
    {//获取某个类型考试的文件和msg
        $data['msg'] = $this->file_model->get_zhuanji_msg($typeid);
        $data['files'] = $this->file_model->get_zhuanji_files($typeid);
            $this->json(
                $data
            );
    }

    public function show_guide_article($guideid=false)
    {//guider文件显示
        if ($guideid===false) {
            show_404();
        }
        $data['article'] = $this->file_model->get_guide_article($guideid);
        $this->load->view('templates/header',$data);
        $this->load->view('article/view',$data);
        $this->load->view('templates/footer');
    }

    public function get_phone_list()
    {
        $data['phonelist'] = $this->file_model->get_guide_list(2);
        $this->json(
            $data
        );
    }
    public function get_zhuanji_files_by_ksid($ksid)
    {
        $data['filelist'] = $this->file_model->get_zhuanji_files($ksid);
        $this->json(
            $data
        );
    }
    //********************************下面接口为给小程序的filepage提供json数据
    public function get_article_json($fileid=FALSE)
    {//只给出文章的内容
        $data = $this->file_model->get_article($fileid);
            $this->json(
                $data
            );
    }
    public function get_filepage_json($fileid)
    {
        $data['article'] = $this->file_model->get_article($fileid);
        $data['eventtime'] = $this->file_model->get_eventtime($fileid);
        $data['notify'] = $this->file_model->get_notify($fileid);
            $this->json(
                $data
            );
    }
    public function get_if_userhasfile($openId,$fileId)
    {
        $ifuserhasfile = $this->file_model->get_userHasFile($openId,$fileId);
        if(empty($ifuserhasfile))
        {
            $this->json(
                ['userhasfile' => false]
            );
        }
        else{
            $this->json(
                ['userhasfile' => true]
            );
        }
    }
    public function insert_user_file($openId,$fileId)
    {
        $this->file_model->insert_user_file($openId,$fileId);
    }
    public function delete_user_file($openId,$fileId)
    {
        $this->file_model->delete_user_file($openId,$fileId);
    }
    /*USER_PAGE*/
    public function get_user_files($openId)
    {
        $data = $this->user_model->get_user_files($openId);
        $this->json($data);
    }
    public function get_user_files_events($openId)
    {//测试OPENID opexV46WZFz9Is4xAI2zZWc4YiQE
        $data = $this->user_model->get_user_files($openId);
        $res = array();
        $i = 0;
        while(!empty($data[$i]))
        {
            $res[$i]["fileinfo"] = $data[$i];
            $res[$i]['eventtime'] = array();
            $temp = $this->user_model->get_file_evnets($data[$i]['fileid']);
            foreach($temp as $v)
            {
                array_push($res[$i]['eventtime'],array('event'=>$v['event'],'starttime'=>$v['startime'],'endtime'=>$v['endtime']));
            }
            $i=$i+1;
        }
        $this->json($res);
    }
    //给正在进行页面提供数据
    public function get_event_files()
    {
        $happening = $this->eventtime_model->get_happenning_event_files();
        $impend = $this->eventtime_model->get_impend_event_files();
        $res['happening'] = array();
        $res['impend'] = array();
        $i = 0;
        $j = 0;
        while(!empty($happening[$i])) //这里没有指针，只能用这个方法判空
        { //用对了方法分分钟解决，这个算法虽然麻烦，但是可以作为例子解决其他的问题,写的挺辛苦留着吧
            //正在发生的数组放到res中，这里完全可以思考封装，赶鸭子先能跑再说
            $res['happening'][$j]["event"] = $happening[$i]["event"];
            $res['happening'][$j]['filelist'] = array();
            while(!empty($happening[$i+1]) && $happening[$i]['event']== $happening[$i+1]['event'])
            {
                array_push($res['happening'][$j]['filelist'],array('endtime'=>$happening[$i]["endtime"],'fileid'=>$happening[$i]['ksfileid'],'filetitle'=>$happening[$i]['filetitle']));
                $i=$i+1;
            }
                array_push($res['happening'][$j]['filelist'],array('endtime'=>$happening[$i]["endtime"],'fileid'=>$happening[$i]['ksfileid'],'filetitle'=>$happening[$i]['filetitle']));
            $i=$i+1;
            $j=$j+1;
        }
        $i = 0;
        $j = 0;
        while(!empty($impend[$i])) //这里没有指针，只能用这个方法判空
        { //用对了方法分分钟解决，这个算法虽然麻烦，但是可以作为例子解决其他的问题,写的挺辛苦留着吧
            $res['impend'][$j]["event"] = $impend[$i]["event"];
            $res['impend'][$j]['filelist'] = array();
            while(!empty($impend[$i+1]) && $impend[$i]['event']== $impend[$i+1]['event'])
            {
                array_push($res['impend'][$j]['filelist'],array('startime'=>$impend[$i]["startime"],'fileid'=>$impend[$i]['ksfileid'],'filetitle'=>$impend[$i]['filetitle']));
                $i=$i+1;
            }
                array_push($res['impend'][$j]['filelist'],array('startime'=>$impend[$i]["startime"],'fileid'=>$impend[$i]['ksfileid'],'filetitle'=>$impend[$i]['filetitle']));
            $i=$i+1;
            $j=$j+1;
        }
        $this->json($res);
        //$this->json($data);
    }
}
