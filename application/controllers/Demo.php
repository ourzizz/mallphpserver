<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('file_model');
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
}
