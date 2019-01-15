 <?php
 /**
  * Goods_model 为了能够从系统中剥离，整个商城模块的文件全部专项专做
  * 这个模型只管理对address这个表的增删改
  * @author qxnbd
  */
 class User_address_model extends CI_Model
 {
     public function __construct() {
         $this->load->database();
     }
     public function select_user_default_address($open_id) {
         $query = $this->db->query(sprintf('select * from user_address where open_id="%s" and default_address=1',$open_id));
         return $query->result_array();
     }

     public function select_user_all_address($open_id) {
         $query = $this->db->query(sprintf('select * from user_address where open_id="%s" order by default_address desc',$open_id));
         return $query->result_array();
     }

     public function delete_address($open_id,$address_id) {
         $this->db->query(sprintf("delete from user_address where open_id='%s' and address_id='%s'",$open_id,$address_id));
     }

     public function update_default_address($open_id,$address_id) {
         $this->db->query(sprintf("UPDATE user_address SET default_address=0 WHERE open_id='%s'",$open_id));
         $this->db->query(sprintf("UPDATE user_address SET default_address=1 WHERE open_id='%s' AND address_id='%s'",$open_id,$address_id));
     }

     public function insert_user_address($open_id,$address) {//
         $this->db->query(sprintf("INSERT INTO `user_address` (`address_id`, `open_id`, `province`, `city`, `county`, `detail`, `name`, `telphone`, `default_address`) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $open_id,$address['province'],$address['city'],$address['county'],$address['detail'],$address['name'],$address['telphone'],$address['default_address']));
     }

     public function update_address($open_id,$address_id,$a){
         switch ($a['key']) {
         case 1:
             $query = $this->db->query(sprintf("update user_address set name='%s' where address_id='%s' and open_id='%s' ",$a['value'],$address_id,$open_id));
             break;
         case 2:
             $query = $this->db->query(sprintf("update user_address set telphone='%s'  WHERE address_id='%s' and open_id='%s' ",$a['value'],$address_id,$open_id));
             break;
         case 3:
             $query = $this->db->query(sprintf("update user_address set province='%s',city='%s',county='%s' where  address_id='%s' and open_id='%s' ",$a['value'][0],$a['value'][1],$a['value'][2],$address_id,$open_id));
             break;
         case 4:
             $query = $this->db->query(sprintf("update user_address set detail='%s' where  address_id='%s' and open_id='%s' ",$a['value'],$address_id,$open_id));
             break;
         }
     }
 }
