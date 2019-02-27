<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\ITunnelHandler as ITunnelHandler;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;

require APPPATH.'business/Order.php';
/**
 * 实现 WebSocket 信道处理器
 * 本文件处理前端捡单、配单、取消送单等一些列商家操作需要信道传送的功能
 */
class OrderTunnelHandler implements ITunnelHandler {
    private $userinfo = NULL;

    public function __construct ($userinfo) {
        $this->userinfo = $userinfo;
    }

    /**
     * 实现 onRequest 方法
     * 在客户端请求 WebSocket 信道连接之后，
     * 会调用 onRequest 方法，此时可以把信道 ID 和用户信息关联起来
     */
    public function onRequest($tunnelId, $tunnelUrl) {
        if ($this->userinfo !== NULL) {
            // 保存 信道ID => 用户信息 的映射
            DB::update('seller',['tunnelId'=>$tunnelId,'tunnelStatus'=>'on'],['open_id'=>$this->userinfo['openId']]);

            echo json_encode([
                'code' => 0,
                'data' => [
                    'connectUrl' => $tunnelUrl,
                    'tunnelId' => $tunnelId
                ]
            ]);
        }
    }

    /**
     * 实现 onConnect 方法
     * 在客户端成功连接 WebSocket 信道服务之后会调用该方法，
     * 此时通知所有其它在线的用户当前总人数以及刚加入的用户是谁
     */
    public function onConnect($tunnelId) {
    }

    /**
     * 实现 onMessage 方法
     * 客户端推送消息到 WebSocket 信道服务器上后，会调用该方法，此时可以处理信道的消息。
     * 在本示例，我们处理 `speak` 类型的消息，该消息表示有用户发言。
     * 我们把这个发言的信息广播到所有在线的 WebSocket 信道上
     * user->tunnel->server->broadcast
     */
    public function onMessage($tunnelId, $type, $content) {
        switch ($type) {
        case 'order'://从订单支付回调口，进入到这个流程
            $order_info = Order::get_order_info($content['order_id']);
            self::broadcast('order', array(
                'who' => "system",
                'order_info' => $order_info,
            ));
            break;
        case 'delivery_cancel'://快递小哥点击取消送单，从这里广播出去
            $order_id = $content['order_id'];
            DB::update('user_order',['seller_act'=>'CANCLE'],['order_id'=>$order_id]);
            self::broadcast('delivery_cancel', array(
                'order_id' => $content['order_id'],
            ));
            break;
        case 'user_signed'://快递小哥已经把单子送到客户手中了
            $order_id = $content['order_id'];
            DB::update('user_order',['seller_act'=>'SIGNED'],['order_id'=>$order_id]);
            self::broadcast('delivery_cancel', array(
                'order_id' => $content['order_id'],
            ));
            break;
        }
    }

    /**
     * 实现 onClose 方法
     * 客户端关闭 WebSocket 信道或者被信道服务器判断为已断开后，
     * 会调用该方法，此时可以进行清理及通知操作
     */
    public function onClose($tunnelId) {
        DB::update('seller',['tunnelStatus'=>'off'],['tunnelId'=>$tunnelId]);
        //if (count($data['connectedTunnelIds']) > 0) {
            //self::broadcast('people', array(
                //'total' => count($data['connectedTunnelIds']),
                //'leave' => $leaveUser,
            //));
        //}
    }

    /**
     * 调用 TunnelService::broadcast() 进行广播
     */
    private static function broadcast($type, $content) {
        //获取所有在线的tunnelId
        $rows = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        $connectedTunnelIds=array();
        foreach($rows as $tid){
            array_push($connectedTunnelIds,$tid->tunnelId);
        }
        $result = TunnelService::broadcast($connectedTunnelIds, $type, $content);
    }

    /**
     * 调用 TunnelService::closeTunnel() 关闭信道
     * @param  String $tunnelId 信道ID
     */
    private static function closeTunnel($tunnelId) {
        TunnelService::closeTunnel($tunnelId);
    }
}
