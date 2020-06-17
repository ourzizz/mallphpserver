<?PHP
//本文件为前端提供商品的信息
use \QCloud_WeApp_SDK\Mysql\Mysql as DB;
use \QCloud_WeApp_SDK\Conf as Conf;
use \QCloud_WeApp_SDK\Cos\CosAPI as Cos;
use \QCloud_WeApp_SDK\Constants as Constants;
defined('BASEPATH') OR exit('No direct script access allowed');
class Gas extends CI_Controller {
    public function getGasHist($houseId){
        //清除所有没有照片的item 
        $sql = "SELECT houseGasHist.*,imgs_buket.url " 
              ."FROM houseGasHist,imgs_buket "
              ."WHERE houseGasHist.houseId='$houseId' "
              ."AND imgs_buket.tableName = 'houseGasHist' "
              ."AND houseGasHist.gasHistId = imgs_buket.type_id "
              ."order by houseGasHist.uploadDate desc";
        $res = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC); 
        $this->json($res);
    }
    public function addNew($houseId){
        //上传逻辑 是先获取houseId 给前端 存储图片到数据库
        //可能用户拿到gasId由于某些原因 没有上传图片，那么这条数据就作废
        $uploadDate = date("Y-m-d H:i:s",time());
        $gasHist = [
            'houseId'=>$houseId,
            'uploadDate'=>$uploadDate,
            //'month'=>substr($uploadDate,0,7)
        ];
        DB::insert('houseGasHist',$gasHist);
        $res['currentMonthRecord'] = DB::row('houseGasHist',['*'],$gasHist);
        $this->json($res);
    }
    public function getCurrentMonthAllList(){
        $currentMonth = date("Y-m",time());
        $sql = "SELECT houseGasHist.*,imgs_buket.url " 
              ."FROM houseGasHist,imgs_buket "
              ."WHERE houseGasHist.uploadDate LIKE '$currentMonth%' "
              ."AND imgs_buket.tableName = 'houseGasHist' "
              ."AND houseGasHist.gasHistId = imgs_buket.type_id "
              ."order by houseGasHist.houseId";
        $res = (DB::raw($sql))->fetchAll(PDO::FETCH_ASSOC); 
        $this->json($res);
    }
    public function updateGas($gasHistId){}

}
