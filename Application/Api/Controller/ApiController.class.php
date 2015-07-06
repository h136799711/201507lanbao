<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 20:22
 */

namespace Api\Controller;


use Think\Controller\RestController;

abstract class ApiController extends RestController{

    public function __construct(){
        parent::__construct();

        if(method_exists($this,"_init")){
            $this->_init();
        }

    }

    protected function _init(){

        //权限验证
        $token  = $this->param_get("token");

        if($this->authorize($token)){

        }else{
            $this->ajaxReturn(array('status'=>-1,'info'=>"Token无效!"),'json');
        }


    }

    protected function authorize($token){
        session('token');
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
        $supportMethod = array();
        $supportMethod = $this->getSupportMethod();
        $data = array("status"=>-1,'supportMethod'=>$supportMethod);
        $this->response($data,"xml","404");
    }

    /**
     * ajax返回，并自动写入token返回
     * @param $data
     * @param int $errcode
     * @internal param $i
     */
    protected function apiReturnWithToken($data,$errcode=0){
        return $this->ajaxReturn(array('errcode'=>$errcode,'token'=>$this->getToken(),'data'=>$data));
    }

    protected function getToken(){

    }

    /**
     * 手动初始化Session
     */
    protected function initSession(){
        $session_id = "";
        session(array('name'=>$session_id,'expire'=>24*3600,'type'=>'Db'));
    }



    abstract function getSupportMethod();

}