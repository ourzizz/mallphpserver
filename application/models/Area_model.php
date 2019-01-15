 <?php
 /**
  * Goods_model 为了能够从系统中剥离，整个商城模块的文件全部专项专做
  * 这个模型只管理对goods这个表的增删改
  * @author qxnbd
  */
 class Area_model extends CI_Model
 {
     public function __construct() {
         $this->load->database();
     }

     public function select_sons_by_pid($pid) {
         $query = $this->db->query(sprintf('select ID,NAME,LEVEL from area where PARENT_ID=%s',$pid));
         $sons = $query->result_array();
         if(count($sons) == 0) {
             return null;
         }
         return $sons;
     }

     public function select_province() {
         $query = $this->db->query('select * from area where PARENT_ID=0');
         $sons = $query->result_array();
         if(count($sons) == 0) {
             return null;
         }
         return $sons;
     }

     public function g_json(&$parient_son_array,$pid) {
         $sons_list = $this->select_sons_by_pid($pid);
         if($sons_list == null) {
             return ;
         }else{
             $node['pid'] = $pid;
             $node['sons_list'] = $sons_list;
             array_push($parient_son_array,$node);
             for($i = 0;$i < count($sons_list);$i++) {
                $this->g_json($parient_son_array,$sons_list[$i]['ID']);
             }
         }
     }
 }
