 <?php
 /**
  * Class File_model
  * @author yourname
  */
 class File_model extends CI_Model
 {
     public function __construct()
     {
         $this->load->database();
     }
     public function get_files_title($typeid = FALSE) {
         //typeid指定类型，为空代表将文件名称全部查出，或者查询指定类型的文件
         if ($typeid == FALSE) {
             $query = $this->db->query('select fileid,pubtime,filetitle,readtime from ksfile order by fileid desc');
             return $query->result_array();
         }
         $querystr = sprintf('select ksfile.fileid,ksfile.pubtime,ksfile.filetitle,ksfile.readtime from kslist,ksfile where kslist.ksid=ksfile.ksid and kslist.kstypeid = %s order by ksfile.pubtime desc',$typeid);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }

     public function get_article($fileid = FALSE) {
         //将指定fileid的文件内容查询抛出
         if ($fileid == FALSE) {
             return [];
         }
         $this->db->query('UPDATE ksfile  SET readtime=readtime+1 WHERE fileid=' . $fileid);
         $query = $this->db->query('select article from ksfile where fileid=' . $fileid);
         return $query->result_array();
     }

     public function get_kstype($fileid = FALSE) {
         //查询类型，并且附上最近一周文件更新的数量，这个查询比较复杂，考虑是否改成view，访问量大担心数据库崩掉
         $query = $this->db->query('SELECT kstype.*, count(if(datediff(curdate(),ksfile.pubtime)<=7,true,null)) as newfile FROM kstype,ksfile,kslist WHERE kstype.kstypeid=kslist.kstypeid and kslist.ksid=ksfile.ksid group by kstype.kstypeid');
         return $query->result_array();
     }

     public function get_headline() {//首页swiper需要3条最新消息
         $query = $this->db->query('SELECT fileid,msgcontent FROM ksmsg  order by ksmsgid desc limit 0,3');
         return $query->result_array();
     }

     public function get_msg($typeid = FALSE) {
         if ($typeid == FALSE) {
             $query = $this->db->query('SELECT fileid,msgcontent FROM ksmsg  order by ksmsgid desc ');
             return $query->result_array();
         }
         $querystr = sprintf('SELECT ksmsg.* FROM ksmsg,ksfile,kslist where ksmsg.fileid=ksfile.fileid and kslist.ksid=ksfile.ksid and ksmsg.deadtime>=now() and kslist.kstypeid = %s order by ksmsgid desc',$typeid);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
//------------------------专业技术考试众多单独给他分配------------------------------------------------------------------------
     public function get_zhuanji_list($typeid = FALSE) {
         //查询专技考试考试列表
         $querystr = sprintf('select kslist.* from ksfile,kslist where ksfile.ksid=kslist.ksid and kslist.kstypeid=3 group by ksfile.ksid order by max(ksfile.fileid) desc;');
         $query = $this->db->query($querystr);
         return $query->result_array();
    }
     public function get_zhuanji_files($ksid) {
         //查询专技考试考试列表
         $querystr = sprintf('select fileid,pubtime,filetitle,readtime from ksfile where ksid=%s order by fileid desc',$ksid);
         $query = $this->db->query($querystr);
         return $query->result_array();
    }
     public function get_zhuanji_msg($ksid) {
         //查询专技考试考试列表
         $querystr = sprintf('SELECT ksmsg.* FROM ksmsg,ksfile,kslist where ksmsg.fileid=ksfile.fileid and kslist.ksid=ksfile.ksid and ksmsg.deadtime>=now() and kslist.ksid = %s order by ksmsgid desc',$ksid);
         $query = $this->db->query($querystr);
         return $query->result_array();
    }
//指南单独作为一个类别对待,其实这里可以考虑代码复用，用继承派生,不管了，程序先上线最重要
     public function get_guide_list($guidetype = 1) {
         //查询到guide的所有标题,默认为1标识指南文件，2为电话指南
         $querystr = sprintf('SELECT guideid,title,readtime,shortname FROM guide where guidetype=%s',$guidetype);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_guide_article($guideid = FALSE) {
         //将指定fileid的文件内容查询抛出
         if ($guideid == FALSE) {
             return NULL;
         }
         $this->db->query('UPDATE guide  SET readtime=readtime+1 WHERE guideid=' . $guideid);
         $query = $this->db->query('select article from guide where guideid=' . $guideid);
         return $query->result_array();
     }
     public function get_new_files() {
         $query = $this->db->query('SELECT fileid,filetitle,pubtime,readtime from ksfile where datediff(curdate(),pubtime)<=8  ORDER by pubtime DESC');
         return $query->result_array();
     }
 }

?>
