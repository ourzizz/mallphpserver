<?php
/**
 * Shopcart_model 为了能够从系统中剥离，整个商城模块的文件全部专项专做
 * 这个模型只管理对goods这个表的增删改
 * @author qxnbd
 */
class Shopcart_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function is_goods_exsit_in_user_cart($open_id,$goods_id) {
        $query = $this->db->query(sprintf(' SELECT * FROM shop_cart WHERE open_id="%s" AND goods_id="%s" ',$open_id,$goods_id));
        return $query->result_array();
    }

    public function insert_user_chose_goods($open_id,$goods_id) {
        //如果数据库中已经存在这条记录，那么只需要增加它的数目
        $res = $this->is_goods_exsit_in_user_cart($open_id,$goods_id);
        if($res == NULL) {//res为空 不存在 直接加入
            $query = $this->db->query(sprintf("INSERT INTO shop_cart (`cart_id`, `timestrap`, `count`, `open_id`, `goods_id`) VALUES (NULL, now(), 1, '%s', '%s')",$open_id,$goods_id));
        }else{
            $query=$this->db->query(sprintf("UPDATE `shop_cart` SET count = count + 1 WHERE `open_id`='%s' AND `goods_id`='%s' ",$open_id,$goods_id));
        }
        //return $query->result_array();
    }

    public function get_cart_num($open_id) {
        $query = $this->db->query(sprintf("select count(*) from shop_cart where open_id='%s'",$open_id));
        return $query->result_array();
    }
    public function select_user_has_goods($open_id) {
        $query = $this->db->query(sprintf("SELECT shop_cart.open_id,shop_cart.count,shop_cart.timestrap,goods.name,goods.price,goods.goods_id,goods.face_img from goods,shop_cart WHERE goods.goods_id = shop_cart.goods_id and shop_cart.open_id = '%s' ",$open_id));
        return $query->result_array();
    }
}
