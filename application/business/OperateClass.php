<?php
/*按道理这里是要对分类表做深度遍历的
 * 但是，但是没有必要，我直接select把所有有孩子的节点select出来
 * 算法简单，比写遍历执行也快，何乐不为，等真的闲的蛋疼的时候再研究算法吧
 * */
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;

class Goods_class{
    private static function get_sons($node){
        $conditions = "lft>$node->lft and rgt<$node->rgt and layer=$node->layer+1"
        DB::select('goods_class',['*'],$conditions);
        return $conditions;
    }
    public static function db_2_json(){
        $conditions = "rgt>lft+1 AND ";//相当于遍历了整棵树，得到所有非叶子节点
        $nodes = DB::select('goods_class',['*'],$conditions);
        $parent_son = [];
        foreach($nodes as $node){
            $sons = DB::select('goods_class',['*'],$conditions);
            $parent_son.push($node,$sons);
        }
    }
}
