<?php
namespace QCloud_WeApp_SDK\Helper;

class Util {
    private static $postPayload = NULL;

    public static function getHttpHeader($headerKey) {//前端做好header包，后端用这个函数取出header中相应的值
        // HTTP_X_WX_CODE HTTP_X_WX_ENCRYPTED_DATA HTTP_X_WX_IV
        // X-WX-Code "033PMcIX0Er92V1QC2JX0WTUHX0PMcIE"
        // X-WX-Encrypted-Data : "w0/RbwPbcu0w9Nw7FXG/R7uYu4K+B39jdSyNbqBXoop7v7S2v8E1b4cWQkduBYNAjJMoM4aTAELjQEuETW0KKD2pPwnsCxb9/Bf25ncGTYS3+VkcmoZi5Ba6ZovZGXoItzhCPlr4QEjcLGZjge+yQg2C/N8Gpo4m7IZL9SuXey/eV0b3xQgpWMDKE6POXzXrDgQXKtvR517e7RquEa2e2gO4KUx9g5uU9mW4gLmClXSYvs1SfgkCn6PSv8JeWoAPQ6Xtey5sLru1a6tBHE3TtOL5iMguzwaIObxanOH3OuSBz4L1Ax+dzG5dgjNcKO9NFSwwz+2vblrPjnvlsuSjf4BWneMKWDpuSU8kFXtx/AzijIfqdMGezYQolGAboklc8s1txk2q4om4FDB5TFsZAUayBdg1nouFHavi3QJ89m6nBjAoaJlCeX7Fup6AqtHuTSCBNfudJoMO2eAVSvtf1w=="
        // X-WX-IV : "k9TzLJDQkwPXL2H7BZcYuQ=="
        $headerKey = strtoupper($headerKey);
        $headerKey = str_replace('-', '_', $headerKey);
        $headerKey = 'HTTP_' . $headerKey;
        return isset($_SERVER[$headerKey]) ? $_SERVER[$headerKey] : '';
    }

    public static function writeJsonResult($obj, $statusCode = 200) {
        header('Content-type: application/json; charset=utf-8');

        http_response_code($statusCode);
        echo json_encode($obj, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);

        Logger::debug("Util::writeJsonResult => [{$statusCode}]", $obj);
    }

    public static function getPostPayload() {
        if (is_string(self::$postPayload)) {
            return self::$postPayload;
        }

        return file_get_contents('php://input');
    }

    public static function setPostPayload($payload) {
        self::$postPayload = $payload;
    }

    public static function getNum($len=5) {//生成len长度的数字字符随机串
        $chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']; 
        $nums = "";
        for ($i = 0; $i < $len; $i++) {
            $id =(int)(rand() % 61); 
            $nums = $nums . $chars[$id]; 
        } 
        return $nums; 
    }
}
