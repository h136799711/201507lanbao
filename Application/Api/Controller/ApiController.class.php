<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace Api\Controller;


use Admin\Model\LogModel;
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
        $access_token = $this->param_get("access_token");
        if(empty($access_token)){
            $access_token = $this->param_post("access_token");
        }
        if(empty($access_token)){
            $this->apiReturnErr("缺失access_token!");
        }

        $_GET['access_token'] = $access_token;
        $resCtrl = new ResourceController();

        $result = $resCtrl->authorize();

        if($result['status'] !== 0){
            $this->apiReturnErr($result['info']);
        }
    }

    protected  function param_get($name){
        return $this->param_filter($name,"get");
    }

    protected  function param_post($name){
        return $this->param_filter($name,"post");
    }

    protected function param_filter($name,$type){
        $name = I($type.'.'.$name,'');
        $name = str_replace(".".$this->_type,"",$name);
        return $name;
    }

    public function _empty(){
        $supportMethod = $this->getSupportMethod();
        $data = array("status"=>404,'supportMethod'=>$supportMethod);
        $this->response($data,"xml","404");
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