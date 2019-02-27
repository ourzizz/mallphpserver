<?PHP
/*
 * bug mysql文件的select被改写，导致很多麻烦,改回去。为信道铺垫
 * 涉及到select的方法调用的语句基本都要重新做
* */
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;
use QCloud_WeApp_SDK\Constants as Constants;
use QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Helper\Util as Util;
use \QCloud_WeApp_SDK\WxPay  as Pay;

require APPPATH.'business/WeixinPay.php';
class Order extends CI_Controller {
    /**
     * 生成订单,将用户选购商品入库 
     * @example 小程序前端给过来的order_info的json格式{'open_id':'xx','goods_list':[{'goods_id':1,'count':1}...],'total_fee':3300,'address_id':1}
     * @param string $tableName 数据库名
     * @param array  $data      要插入的数据
     */
    public function pay(){
        if(isset($_POST['order_info'])) {
            $timestamp    = time();
            $order_info   = json_decode($_POST['order_info'],true);
            $order_id     = $timestamp.Util::getNum(5);//时间戳加上5位随机码生成订单号
            $appid        = Conf::getAppId();
            $key          = Conf::getKey();
            $mch_id       = Conf::getMchId();
            $openid       = $order_info['open_id'];
            $total_fee    = $order_info['total_fee'];
            $out_trade_no = $order_id;
            $body = "bookstore";
            $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
            $return = $weixinpay->pay();
            $return['order_id'] = $order_id;
            $order_info['order_id'] = $order_id;
            $order_info['timestamp'] = $timestamp;
            $order_info = array_merge($order_info,$return);
            $this->store_order($order_info);
            $this->json($return);
        }
    }

    /*
     *取出订单详细信息
     */
    public function get_order_info($order_id){
        $order = DB::row('user_order',['*'],"order_id='$order_id'");
        $address = DB::row('user_address',['*'],"address_id='$order->address_id'");
        $goods_list = DB::select('goods_in_order',['*'],"order_id='$order_id'");
        foreach($goods_list as &$goods){
            $goods_id = $goods->goods_id;
            $goods_info = DB::select('goods',['name','face_img','price'],"goods_id='$goods_id'");
            $goods = array_merge((array)($goods),(array)($goods_info[0]));
        }
        $res = compact('order','address','goods_list');
        return $res;
    }

    public function re_pay($order_id){
        $this->json($this->get_order_info($order_id));
    }

    /*
     *用户确认收货
     *
     */
    public function user_sign_order($open_id,$order_id){
        $condition = "open_id='$open_id' AND order_id='$order_id' ";
        DB:: update('user_order',['logistics_status'=>'SIGNED'],$condition);
    }

    /*
     *取出待签收订单的信息
     */
    public function get_wait_sign_order_list($open_id){
        $conditions = "pay_status='SUCCESS' AND logistics_status is NULL AND refund_reason is NULL";
        $row = DB::select('user_order',['order_id'],$conditions,'and','order by timeStamp desc');
        $wait_pay_orders = [];
        foreach($row as $order){
            array_push($wait_pay_orders,$this->get_order_info($order->order_id));
        }
        $this->json($wait_pay_orders);
    }

    /*
     *取出已经完成订单的信息
     */
    public function get_finished_order_list($open_id){
        $conditions = "pay_status='SUCCESS' AND logistics_status = 'SIGNED'";
        $row = DB::select('user_order',['order_id'],$conditions,'and','order by timeStamp desc');
        $orders = [];
        foreach($row as $order){
            array_push($orders,$this->get_order_info($order->order_id));
        }
        $this->json($orders);
    }

    /*
     *取出待支付订单的信息
     佳佳j
     */
    public function get_wait_pay_order($open_id){
        $conditions = "unix_timestamp(now()) - timeStamp <= 1800 and open_id='$open_id' and pay_status!='SUCCESS'";
        $row = DB::select('user_order',['order_id'],$conditions,'and','order by timeStamp desc');
        $wait_pay_orders = [];
        foreach($row as $order){
            array_push($wait_pay_orders,$this->get_order_info($order->order_id));
        }
        $this->json($wait_pay_orders);
    }

    /*
     *取出用户自己申请退款订单的信息
     */
    public function get_refund_list($open_id) {
        $conditions = "refund_reason IS NOT NULL AND open_id = '$open_id'";
        $row = DB::select('user_order',['order_id'],$conditions);
        $refund_list = [];
        foreach($row as $order){
            array_push($refund_list,$this->get_order_info($order->order_id));
        }
        $this->json($refund_list);
    }

    /*
     *存储订单信息
     */
    public function store_order($order_info) {
        $order = [
            'order_id'   => $order_info['order_id'],
            'open_id'    => $order_info['open_id'],
            'address_id' => $order_info['address_id'],
            'timestamp'  => $order_info['timestamp'],
            'order_date' => date("Y-m-d H:i:s",time()),
            'total_fee'  => $order_info['total_fee'],
            'nonceStr'   => $order_info['nonceStr'],
            'paySign'    => $order_info['paySign'],
            'package'    => $order_info['package'],
        ];
        DB::insert('user_order',$order);
        foreach($order_info['goods_list'] as $goods) {
            $goods['order_id'] = $order['order_id'];
            DB::insert('goods_in_order',$goods);
            //从购物车删除商品
            DB::delete('shop_cart',['open_id'=>$order['open_id'],'goods_id'=>$goods['goods_id']]);
        }
    }

    /*
     * 删除订单
     * 订单号唯一，所以只用给出order_id即可
    * */
    public function delete_order($order_id){
        DB::delete('goods_in_order',['order_id'=>$order_id]);
        $row = DB::delete('user_order',['order_id'=>$order_id]);
        echo $row;
    }

    public function pay_success($order_id){
        $res = $this->get_order_info($order_id);
        $this->json($res);
    }

    public function get_seller_telphone($order_id){
        $seller_id = (DB::row('user_order',['picker_id'],['order_id'=>$order_id]))->picker_id;
        $telphone =  DB::row('seller',['telphone'],['open_id'=>$seller_id]);
        if(!isset($telphone)) {
            $telphone =  DB::row('seller',['telphone'],['role'=>'root']);
        }
        $this->json($telphone);
    }

    public function request_refund(){
        //if($this->checkLogin()){
        $open_id = $_POST['open_id'];
        $order_id = $_POST['order_id'];
        $reason = $_POST['reason'];
        if(isset($open_id) && isset($order_id) && isset($reason)){
            $conditions = "open_id='$open_id' AND order_id='$order_id' ";
            DB::update("user_order",['refund_reason'=>$reason,'refund_status'=>'W'],$conditions);
            echo "true";
            //$this->refund_broadcast($order_id);
            $this->refund_broadcast('refund',['order_id'=>$order_id]);
        }else{
            echo "false";
        }
        //}
    }


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

    public function refund_broadcast($order_id){
        $rows = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        $connectedTunnelIds=array();
        foreach($rows as $tid){
            array_push($connectedTunnelIds,$tid->tunnelId);
        }
        $type = 'refund';
        $content = ['order_id'=>$order_id];
        $result = TunnelService::broadcast($connectedTunnelIds, $type, $content);
    }

    public function broadcast($type,$content){
        //$order_id = '1550849044tpBOa';
        $rows = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        $connectedTunnelIds=array();
        foreach($rows as $tid){
            array_push($connectedTunnelIds,$tid->tunnelId);
        }
        //$type = 'refund';
        //$content = ['order_id'=>$order_id];
        $result = TunnelService::broadcast($connectedTunnelIds, $type, $content);
    }

    public function test(){
        $order_id = '1550849044tpBOa';
        $rows = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        $connectedTunnelIds=array();
        foreach($rows as $tid){
            array_push($connectedTunnelIds,$tid->tunnelId);
        }
        $type = 'refund';
        $content = ['order_id'=>$order_id];
        $result = TunnelService::broadcast($connectedTunnelIds, $type, $content);
    }
}

