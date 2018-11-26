
<?php
/**
 * Class File_model
 * @author yourname
 */
class Eventtime_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }
    public function get_happenning_event_files()
    {//正在发生
        $querystr = sprintf("SELECT eventtime.*,ksfile.filetitle from eventtime,ksfile where eventtime.ksfileid = ksfile.fileid AND now()>eventtime.startime AND now()<eventtime.endtime order by eventtime.eventid,eventtime.endtime desc");;
        //$querystr = "SELECT eventtime.*,ksfile.filetitle from eventtime,ksfile where eventtime.ksfileid = ksfile.fileid order by event";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
    public function get_impend_event_files()
    {//即将开始
        //$querystr = sprintf("SELECT eventtime.*,ksfile.filetitle from eventtime,ksfile where eventtime.ksfileid = ksfile.fileid AND now()>eventtime.startime AND now()<eventtime.endtime ",$openId);;
        $querystr = "SELECT eventtime.*,ksfile.filetitle from eventtime,ksfile where eventtime.ksfileid = ksfile.fileid AND now()<eventtime.startime order by eventtime.eventid,eventtime.startime";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    public function get_new_files_bytype($typeid)
    {
        $querystr = sprintf("SELECT ksfile.fileid,ksfile.filetitle,ksfile.pubtime,ksfile.readtime FROM typekslist,ksfile WHERE typekslist.ksid=ksfile.ksid AND typekslist.kstypeid=%s AND datediff(curdate(),ksfile.pubtime)<=7 order by pubtime desc",$typeid);
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    public function get_happenning_event_files_bytype($typeid)
    {
        $querystr = sprintf("select ksfile.fileid,ksfile.filetitle,ksfile.pubtime,ksfile.readtime,eventtime.* from typekslist,ksfile,eventtime where typekslist.ksid=ksfile.ksid and eventtime.ksfileid=ksfile.fileid and  typekslist.kstypeid=%s and eventtime.startime<now() and eventtime.endtime>now() order by ksfile.pubtime desc",$typeid);
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
}

?>
