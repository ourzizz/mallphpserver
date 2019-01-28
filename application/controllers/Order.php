<?PHP
//1-26支付接口迟迟办不下来，真是打击积极性，不要紧我们跳过支付继续前进
//今日必须完成生成订单接口
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Helper\Util as Util;

class Order extends CI_Controller {

    /**
     * 生成订单,将用户选购商品入库 
     * @example 小程序前端给过来的order_info的json格式{'open_id':'xx','goods_list':[{'goods_id':1,'count':1}...],'total_fee':3300,'address_id':1}
     * @param string $tableName 数据库名
     * @param array  $data      要插入的数据
     */

    public function index(){
        if(isset($_POST['order_info'])) {
            $order_info = json_decode($_POST['order_info'],true);
            $order_id = time().Util::getNum(5);//时间戳加上5位随机码生成订单号
            $order = [
                'order_id'       => $order_id,
                'open_id'        => $order_info['open_id'],
                'address_id'     => $order_info['address_id'],
                'total_fee'      => $order_info['total_fee'],
                'pick_time'      => time(),
            ];
            DB::insert('user_order',$order);
            foreach($order_info['goods_list'] as $goods) {
                $goods['order_id'] = $order_id;
                DB::insert('goods_in_order',$goods);
            }
        }
    }

    //function insert
}

