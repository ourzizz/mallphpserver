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

require APPPATH.'business/OrderTunnelHandler.php';

class Test_tunnel extends CI_Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $result = LoginService::check();
            
            if ($result['loginState'] === Constants::S_AUTH) {
                $handler = new OrderTunnelHandler($result['userinfo']);
                TunnelService::handle($handler, array('checkLogin' => TRUE));
            } else {
                $this->json([
                    'code' => -1,
                    'data' => []
                ]);
            }
        } else {//POST就是信道服务器会从这个入口把信息传入
            $handler = new OrderTunnelHandler([]);
            TunnelService::handle($handler, array('checkLogin' => FALSE));
        }
    }
}
