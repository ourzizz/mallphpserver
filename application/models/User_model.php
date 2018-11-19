 <?php
 /**
  * Class File_model
  * @author yourname
  */
 class User_model extends CI_Model
 {
     public function __construct()
     {
         $this->load->database();
     }
     public function get_user_files($openId)
     {
         $querystr = sprintf("SELECT ksfile.filetitle,ksfile.fileid from ksfile,userhasfiles where userhasfiles.openid='%s' and userhasfiles.fileid=ksfile.fileid ",$openId);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_file_evnets($fileId)
     {
         $querystr = sprintf("SELECT * FROM eventtime WHERE ksfileid='%s' and now()>startime and now()<endtime",$fileId);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_user_files_events($openId)
     {
         $querystr = sprintf("SELECT DISTINCT ksfile.fileid,ksfile.filetitle,eventtime.event,eventtime.startime,eventtime.endtime FROM userhasfiles,ksfile,eventtime WHERE userhasfiles.openId='%s' AND eventtime.ksfileid = ksfile.fileid AND ksfile.fileid=userhasfiles.fileid",$openId);//查询得出的结果中没有event的行都不见了
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_user_info()
     {
         $querystr = sprintf("select user_info from cSessionInfo");//查询得出的结果中没有event的行都不见了
         $query = $this->db->query($querystr);
         return $query->result_array();

     }
 }

?>
