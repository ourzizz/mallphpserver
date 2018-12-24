 <?php
 /**
  * Goods_model 为了能够从系统中剥离，整个商城模块的文件全部专项专做
  * 这个模型只管理对goods这个表的增删改
  * @author qxnbd
  */
 class Goods_model extends CI_Model
 {
     public function __construct()
     {
         $this->load->database();
     }

     public function get_goods_by_class_id($class_id = FALSE) {
         $query = $this->db->query(sprintf('select * from goods where class_id=%s',$class_id));
         return $query->result_array();
     }
 }
