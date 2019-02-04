<?PHP
//1-26支付接口迟迟办不下来，真是打击积极性，不要紧我们跳过支付继续前进
//今日必须完成生成订单接口
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Helper\Util as Util;
use \QCloud_WeApp_SDK\WxPay  as Pay;

class Order extends CI_Controller {
    /**
     * 生成订单,将用户选购商品入库 
     * @example 小程序前端给过来的order_info的json格式{'open_id':'xx','goods_list':[{'goods_id':1,'count':1}...],'total_fee':3300,'address_id':1}
     * @param string $tableName 数据库名
     * @param array  $data      要插入的数据
     */
    public function pay(){
        if(isset($_POST['order_info'])) {
            $order_info   = json_decode($_POST['order_info'],true);
            $order_id     = time().Util::getNum(5);//时间戳加上5位随机码生成订单号
            $appid        = Conf::getAppId();
            $key          = Conf::getKey();
            $mch_id       = Conf::getMchId();
            $openid       = $order_info['open_id'];
            $total_fee    = $order_info['total_fee'];
            $out_trade_no = $order_id;
            
            $order = [
                'order_id'   => $order_id,
                'open_id'    => $order_info['open_id'],
                'address_id' => $order_info['address_id'],
                'total_fee'  => $order_info['total_fee'],
                'pick_time'  => time(),
            ];
            DB::insert('user_order',$order);
            foreach($order_info['goods_list'] as $goods) {
                $goods['order_id'] = $order_id;
                DB::insert('goods_in_order',$goods);
                //从购物车删除商品
                DB::delete('shop_cart',['open_id'=>$openid,'goods_id'=>$goods['goods_id']]);
            }

            $body = "bookstore";
            $weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);
            $return = $weixinpay->pay();
            $return['order_id'] = $order_id;
            $this->json($return);
        }
    }
    //public function order_storage($order_info) {
    //}
    public function pay_success($order_id){
        $row = DB::select('user_order',['total_fee','address_id'],"order_id='$order_id'");
        //print($row[0]['total_fee']);
        //print_r ($row[0]);
        $order_info['total_fee'] = $row[0]['total_fee'];
        $address_id = $row[0]['address_id'];
        $row = DB::select('user_address',['name','telphone','province','city','county','detail'],"address_id='$address_id'");
        $order_info['address'] = $row[0];
        $this->json($order_info);
    }
}

class WeixinPay {
    protected $appid;
    protected $mch_id;
    protected $key;
    protected $openid;
    protected $out_trade_no;
    protected $body;
    protected $total_fee;
    function __construct($appid, $openid, $mch_id, $key,$out_trade_no,$body,$total_fee) {
        $this->appid = $appid;
        $this->openid = $openid;
        $this->mch_id = $mch_id;
        $this->key = $key;
        $this->out_trade_no = $out_trade_no;
        $this->body = $body;
        $this->total_fee = $total_fee;
    }
    public function pay() {
        //统一下单接口
        $return = $this->weixinapp();
        return $return;
    }
    //统一下单接口
    private function unifiedorder() {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $parameters = array(
            'appid' => $this->appid, //小程序ID
            'mch_id' => $this->mch_id, //商户号
            'nonce_str' => $this->createNoncestr(), //随机字符串
            'body' => 'test', //商品描述
            'out_trade_no'=> $this->out_trade_no,
            //'total_fee' => $this->total_fee * 100, //总金额 单位 分
            'total_fee' => 1, //总金额 单位 分
            'spbill_create_ip' => '192.168.0.161', //终端IP
            //'notify_url' => 'https://www.alemao.club/bjks/index.php?/Order/notify', //通知地址  确保外网能正常访问
            'notify_url' => 'http://bijiekaoshi.com/pay/index.php', //通知地址  确保外网能正常访问
            'openid' => $this->openid, //用户id
            'trade_type' => 'JSAPI'//交易类型
        );
        //统一下单签名
        $parameters['sign'] = $this->getSign($parameters);
        $xmlData = $this->arrayToXml($parameters);
        $return = $this->xmlToArray($this->postXmlCurl($xmlData, $url, 60));

        $arr = array(
            'nonce_str' => $parameters['nonce_str'],
            'sign' => $parameters['sign']
        );
        DB::update('user_order',$arr,"order_id='$this->out_trade_no'");
        //print_r($return);
        return $return;
    }

    private static function postXmlCurl($xml, $url, $second = 30) 
    {//调用微信支付接口获取pre_id
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            //print_r($data);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
    //数组转换成xml
    private function arrayToXml($arr) {
        $xml = "<root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</root>";
        return $xml;
    }
    //xml转换成数组
    private function xmlToArray($xml) {
        //禁止引用外部xml实体 
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }
    //微信小程序接口
    private function weixinapp() {
        //统一下单接口
        $unifiedorder = $this->unifiedorder();
        $parameters = array(
            'appId' => $this->appid, //小程序ID
            'timeStamp' => '' . time() . '', //时间戳
            'nonceStr' => $this->createNoncestr(), //随机串
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'], //数据包Undefined index: prepay_id
            'signType' => 'MD5'//签名方式
        );
        //签名
        $parameters['paySign'] = $this->getSign($parameters);
        return $parameters;
    }
    //作用：产生随机字符串，不长于32位
    private function createNoncestr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    //作用：生成签名
    private function getSign($Obj) {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        //签名步骤二：在string后加入KEY
        $String = $String . "&key=" . $this->key;
        //签名步骤三：MD5加密
        $String = md5($String);
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        return $result_;
    }
    ///作用：格式化参数，签名过程需要使用
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
}
