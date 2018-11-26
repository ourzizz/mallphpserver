 <?php
 /**
  * Class File_model
  * @author yourname
  */
 class Home_model extends CI_Model
 {
     public function __construct()
     {
         $this->load->database();
     }
     public function get_county()
     {
         $querystr = sprintf("SELECT countyid AS id,countyname FROM county");;
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_filesby_ksid_countyid($ksid,$countyid)
     {
         $querystr = sprintf('select fileid,pubtime,filetitle,readtime from ksfile where ksid=%s and countyid=%s order by fileid desc',$ksid,$countyid);
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
     public function get_ing_event()
     {
         $querystr = sprintf("SELECT * FROM eventtime where now()>startime and now()<endtime ORDER BY endtime");
         $query = $this->db->query($querystr);
         return $query->result_array();
     }
 }


?>
