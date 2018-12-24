<?php
/**
 * Class File_model
 * 这个模型中的全部接口全部只为小程序服务，其他复杂逻辑需要浏览器端的下一步有业务扩张再考虑抛给laravel开发
 * @author yourname
 */
class Node
{
    public $class_id = 0;
    public $class_name = '';
    public $lft = 0;
    public $rgt = 0;
    public $layer = 0;
    public function __construct($class_id,$class_name,$lft,$rgt,$layer){
        $this->class_id =$class_id;
        $this->class_name = $class_name;
        $this->lft = $lft;
        $this->rgt = $rgt;
        $this->layer = $layer;
    }
}

class Goods_class_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_sons($node) {
        $querystr = sprintf('select * from goods_class where lft>%s and rgt<%s and layer=%s',$node.lft,$node.rgt,$node.layer + 1)#限定层级为当前下一层就可以拿到所有儿子不要孙子辈
            $query = $this->db->query($querystr);
        return $query->result_array();
    }

    public function get_node_by_lft($lft)
    {
        $querystr = sprintf('select * from goods_class where lft=%s',lft);
        $query = $this->db->query($querystr);;
        return $query->result_array();
    }
}
