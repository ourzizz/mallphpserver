 <?php
 /**
  * Goods_model 为了能够从系统中剥离，整个商城模块的文件全部专项专做
  * 这个模型只管理对goods这个表的增删改
  * @author qxnbd
  */
 class User_address_model extends CI_Model
 {
     public function __construct()
     {
         $this->load->database();
     }

     public function select_user_default__address($class_id = FALSE) {//获取用户的默认地址
         $query = $this->db->query(sprintf('select * from goods where class_id=%s',$class_id));
         return $query->result_array();
     }

     public function select_goods_by_goodsid($goods_id = FALSE) {
         if($goods_id == FALSE) {
             return null;
         }
         $query = $this->db->query(sprintf('select face_img,name,price from goods where goods_id=%s',$goods_id));
         return $query->result_array();
     }
 }
