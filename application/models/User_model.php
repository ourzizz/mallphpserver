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
     public function get_user_files_events($openId)
     {
         $querystr = sprintf("SELECT DISTINCT ksfile.fileid,ksfile.filetitle,eventtime.event,eventtime.startime,eventtime.endtime FROM userhasfiles,ksfile,eventtime WHERE userhasfiles.openId='%s' AND eventtime.ksfileid = ksfile.fileid AND ksfile.fileid=userhasfiles.fileid",$openId);
         $query = $this->db->query($querystr);
         return $query->result_array();

     }
 }

?>
