<?php
//检测敏感词 和校验不规范图片
namespace QCloud_WeApp_SDK\Myapi;
use \QCloud_WeApp_SDK\Helper\Request as Request;
use \QCloud_WeApp_SDK\Constants as Constants;
use \QCloud_WeApp_SDK\Conf as Conf;

class Mingan
{
    public static function get_access_token(){
        $requestParams = [
            'grant_type' => 'client_credential',
            'appid' => 'wx98b3be7df79c0bdc',
            'secret' => '0d56c6ac9098cbbc0d58a7495a2d7591',
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/token?' . http_build_query($requestParams);
        list($status, $body) = array_values(Request::get([
            'url' => $url,
            'timeout' => Conf::getNetworkTimeout()
        ]));
        return $body['access_token'];
    }
    public static function check_words($text){
        $access_token = self:: get_access_token();
        $options = [];
        $data['content'] = $text;
        $options['url'] = 'https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $access_token;
        $options['data'] = json_encode($data, JSON_UNESCAPED_UNICODE);//关键步骤
        $options['timeout'] = Conf::getNetworkTimeout();
		$options = array_merge_recursive($options, array(
            'method' => 'POST',
            'headers' => array('Content-Type: application/json; charset=utf-8'),
        ));
        list($status, $body) = array_values(Request::send($options));
        return $body;
    }
    public static function check_img($img) {
        //$img = file_get_contents($img);//如果给的是url需要先下载
		$token = self:: get_access_token();
        $uid = uniqid(); //上传文件过多会覆盖，生成随机文件，验证后删除
        $filePath = '/dev/shm/' . $uid . '.png';
        file_put_contents($filePath, $img);
        $obj = new \CURLFile(realpath($filePath));
        $obj->setMimeType("image/jpeg");
        $file['media'] = $obj;
        $url = "https://api.weixin.qq.com/wxa/img_sec_check?access_token=$token";
        $info = self::http_request($url,$file);
        unlink($filePath);
        return json_decode($info,true);
    }

	public static function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        file_put_contents('/tmp/heka_weixin.' . date("Ymd") . '.log', date('Y-m-d H:i:s') . "\t" . $output . "\n", FILE_APPEND);
        return $output;
    }
}
