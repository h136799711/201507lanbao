<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace Api\Controller;


use Admin\Model\LogModel;
use Api\Service\EncryptService;
use Common\Model\WeixinLogModel;
use Think\Controller\RestController;

/**
 * 接口基类
 * Class ApiController
 *
 * @author 老胖子-何必都 <hebiduhebi@126.com>
 * @package Api\Controller
 */
abstract class ApiController extends RestController{

    private $encrypt_key = "";
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();

        if(method_exists($this,"_init")){
            $this->_init();
        }

    }

    protected function _init(){
        $access_token = I("get.access_token");
        if(empty($access_token)){
            $access_token = I("post.access_token");
        }
        if(empty($access_token)){
            $this->apiReturnErr("缺失access_token!");
        }

        $_GET['access_token'] = $access_token;
        //TODO: 对传输数据进行解密
//        $data = $_POST['data'];
//        $_POST = array_merge($_POST,$data);

        $resCtrl = new ResourceController();

        $result = $resCtrl->authorize();

        if($result['status'] !== 0){
            $this->apiReturnErr($result['info']);
        }
    }


    public function _empty(){
//        $supportMethod = $this->getSupportMethod();
        $data = array("status"=>404,'info'=>'访问地址错误!');
        $this->response($data,"json","404");
    }

    /**
     * ajax返回
     * @param $data
     * @internal param $i
     */
    protected function apiReturnSuc($data){
         $this->ajaxReturn(array('code'=>0,'data'=>$data));
    }

    /**
     * ajax返回，并自动写入token返回
     * @param $data
     * @param int $code
     * @internal param $i
     */
    protected function apiReturnErr($data,$code=-1){
         $this->ajaxReturn(array('code'=>$code,'data'=>$data));
    }

    abstract function getSupportMethod();


}