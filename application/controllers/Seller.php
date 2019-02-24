<?php
/*本接口为商家提供服务，商家接单、退款等功能均在此
 *
 * 数据库的seller_act
 * @WAIT_PICK 待接单
 * @ASSEMBLE  配单中
 * @DELIVERY  投递中
 * @SIGNED    客户签收
* */
use \QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Helper\Util as Util;
use QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\WxPay  as Pay;

require APPPATH.'business/Order.php';
class Seller extends CI_Controller {

    /**
     *这个部分涉及到比较敏感的东西，订单和钱
     *还是需要加入身份验证的方法，这个地方先预留接口
     */
    private function checkLogin(){
            $result = LoginService::check();
            if ($result['loginState'] === Constants::S_AUTH) {
                return true;
            } else {
                $this->json([
                    'code' => -1,
                    'data' => []
                ]);
                return false;
            }
    }

    /**
     *取出还没有接单的订单发给商家
     * @example 小程序前端给过来的order_info的json格式{'open_id':'xx','goods_list':[{'goods_id':1,'count':1}...],'total_fee':3300,'address_id':1}
     */
    public function get_wait_pick_orders(){
        if($this->checkLogin()){
            $rows = DB::select('user_order',['*'],"seller_act='WAIT_PICK'");
            $orders = [];
            foreach($rows as $order){
                array_push($orders,Order::get_order_info($order->order_id));
            }
            $this->json($orders);
        }
    }

    /**
     *取出还没有发货的单子
     * @example 小程序前端给过来的order_info的json格式{'open_id':'xx','goods_list':[{'goods_id':1,'count':1}...],'total_fee':3300,'address_id':1}
     */
    public function get_delivery_orders(){
        if($this->checkLogin()){
            $rows = DB::select('user_order',['*'],"seller_act='DELIVERY'");
            $orders = [];
            foreach($rows as $order){
                array_push($orders,Order::get_order_info($order->order_id));
            }
            $this->json($orders);
        }
    }

    public function pick_orders(){
        if(self::checkLogin()){
            $picker_id =  $_POST['open_id'];
            $order_id =  $_POST['order_id'];
            $rows = DB::update('user_order',['picker_id'=>$picker_id,'seller_act'=>'ASSEMBLE'],['order_id'=>$order_id],'and');
        }
    }

    public function assemble_finish(){
        if(self::checkLogin()){
            $order_id =  $_POST['order_id'];
            $rows = DB::update('user_order',['seller_act'=>'DELIVERY'],['order_id'=>$order_id],'and');
        }
    }

    public function dilevery_orders(){
    }

    /**
     *生成新订单需要对信道进行广播
     */
    public function order_broadcast(){
    }

    /**
     *同意退款
     */
    public function agree_refund(){
    }

}
