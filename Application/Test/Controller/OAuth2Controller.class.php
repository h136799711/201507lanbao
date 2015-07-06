<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 15:04
 */
namespace Test\Controller;

use OAuth2\Request;
use Test\Api\ServerApi;
use Think\Controller;

class OAuth2Controller extends Controller{

    public function index(){
        $this->display();
    }

    /**
     * POST:
     */
    public function token(){
        $api = new ServerApi();
        $server = $api->init();
        $result = $server->handleTokenRequest(Request::createFromGlobals());
//        dump($result);
        $params = $result->getParameters();
        if($result->getStatusCode() != 200){
            $this->ajaxReturn(array('errcode'=>$result->getStatusCode(),'info'=>$params),"json");
        }else{
//            dump($result);
            $this->ajaxReturn(array('errcode'=>0,'info'=>$params),"json");
        }
//        ->send();
    }

    public function resource(){
        $api = new ServerApi();
        $server = $api->init();
        if (!$server->verifyResourceRequest(Request::createFromGlobals())) {
            $server->getResponse()->send();
            die;
        }
        $this->ajaxReturn(array('errcode'=>0,'info'=>'你可以访问我的API'),"json");
    }

}