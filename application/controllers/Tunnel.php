<?php
/*
 * APPPATH:'/data/wwwroot/default/bjks/application/'
 *
 *
 * */
defined('BASEPATH') OR exit('No direct script access allowed');

use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Tunnel\TunnelService as TunnelService;
use \QCloud_WeApp_SDK\Auth\LoginService as LoginService;
use QCloud_WeApp_SDK\Constants as Constants;

require APPPATH.'business/ChatTunnelHandler.php';

class Tunnel extends CI_Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $result = LoginService::check();
            
            if ($result['loginState'] === Constants::S_AUTH) {
                $handler = new ChatTunnelHandler($result['userinfo']);
                TunnelService::handle($handler, array('checkLogin' => TRUE));
            } else {
                $this->json([
                    'code' => -1,
                    'data' => []
                ]);
            }
        } else {//POST就是信道服务器会从这个入口把信息传入
            $handler = new ChatTunnelHandler([]);
            TunnelService::handle($handler, array('checkLogin' => FALSE));
        }
    }
    public function notify_seller(){
        $rows = DB::select("seller",['tunnelId'],['tunnelStatus'=>'on']);
        $connectedTunnelIds=array();
        foreach($rows as $tid){
            array_push($connectedTunnelIds,$tid->tunnelId);
        }
        $content =json_decode('{"who":{"openId":"opexV46WZFz9Is4xAI2zZWc4YiQE","nickName":"海","gender":1,"language":"zh_CN","city":"Bijie","province":"Guizhou","country":"China","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/UBJru313UuUKSWlZcqXTIVVsJvESlqHPOjbic97OZkUWjyPolAYc8b4DaPpyfoYF0mQQcSdXZw85Dic6HA3ib7O8w/132","watermark":{"timestamp":1550332447,"appid":"wxfa21ea4bdaef03e9"}},"word":"用户下单"}'); 
        $result = TunnelService::broadcast($connectedTunnelIds, 'speak', $content);
        $this->json($result);
    }
}
