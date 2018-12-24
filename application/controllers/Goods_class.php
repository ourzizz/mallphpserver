<?PHP
//本文件，为商品分类提供基础操作
defined('BASEPATH') OR exit('No direct script access allowed');
class Demo extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('goods_class_model');
        $this->load->helper('url_helper');
    }

    public function vist_tree($node,$res_array){ //递归输出所有叶子节点的路径
        if (($node->rgt - node->lft) == 1){
        ¦  return ;
        }
        ¦   else{
            ¦   $res_array['parent_id'] = node->class_id;
            ¦   $res_array['son_list'] = this->get_son_nodes(node);
            ¦   res.append(parent_son);
            ¦   for son in parent_son['son_list']:
            ¦   ¦   self.generate_parent_sons(son,res)
    }

        public function get_parent_son_json($lft) {
            //前端给lft值,后端返回{parentid,sonlist}结构
            $node = $this->goods_class_model->get_node_by_lft($lft);
            $parent_son = [];
            $this->vist_tree($node,$parent_son);
        }
    }
}
