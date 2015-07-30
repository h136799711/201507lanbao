<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace Api\Controller;


use OAuth2Manage\Api\AccessTokensApi;
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
    private $client_id = "";
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
        //TODO: 对POST过来的传输数据进行解密,并将解密后的数据放入$_POST变量中

        $resCtrl = new ResourceController();

        $result = $resCtrl->authorize();

        if($result['status'] !== 0){
            $this->apiReturnErr($result['info'],$result['status']);
        }
        $result = apiCall(AccessTokensApi::GET_INFO,array(array('access_token'=>$access_token)));
        if($result['status']){
            $this->client_id = $result['info']['client_id'];
        }
    }


    public function _empty(){
        $this->apiReturnErr("访问地址错误");
    }

    /**
     * ajax返回
     * @param $data
     * @internal param $i
     */
    protected function apiReturnSuc($data){
         $this->ajaxReturn(array('code'=>0,'data'=>$data),"json");
    }

    /**
     * ajax返回，并自动写入token返回
     * @param $data
     * @param int $code
     * @internal param $i
     */
    protected function apiReturnErr($data,$code=-1){
        $this->ajaxReturn(array('code'=>$code,'data'=>$data),"json");
    }

    public function _post($key,$default=''){
        return I("post.".$key,$default);
    }


    abstract function getSupportMethod();


}