<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 15:04
 */
namespace Api\Controller;

use OAuth2\Request;
use Test\Api\ServerApi;

class OAuth2Controller extends ApiController{

    public function index(){
        $this->display();
    }

    /**
     * POST:
     */
    public function token(){
        $api = new OAuth2Service();
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
        $api = new OAuth2Service();
        $server = $api->init();
        if (!$server->verifyResourceRequest(Request::createFromGlobals())) {
            $params = $result->getParameters();
            $this->ajaxReturn(array('errcode'=>$result->getStatusCode(),'info'=>$params),"json");
//            $server->getResponse()->send();
//            die;
        }

        $this->ajaxReturn(array('errcode'=>0,'info'=>'你可以访问我的API'),"json");
    }

    function getSupportMethod()
    {
        return array(
            'type'=>'POST',
            'uri'=>'/Api/OAuth2/token',
            'params'=>'grant_type:[]',
            'description'=>'POST方式传入参数，验证通过后可换取access_token',
            'author'=>'hbd [hebiduhebi@126.com]',
            'update_date'=>'2015-07-06',
            'version'=>'1.0.0',
        );
    }
}