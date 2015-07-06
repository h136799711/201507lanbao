<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 14:22
 */
namespace Api\Controller;

use Common\Api\AccountApi;

class UserController extends ApiController{

    protected  $allowType = array("json","rss","html");

    function getSupportMethod()
    {
        // TODO: Implement getSupportMethod() method.
        return array(
            'login_post'=>array(
                'param'=>'',
                'return'=>'array(\"status\"=>返回状态,\"info\"=>\"信息\")',
                'author'=>'hebidu [hebiduhebi@163.com]',
                'version'=>'1.0.0',
                'description'=>'用户登录验证',
                'demo_url'=>'http://manual.itboye.com#',
            ),
            );

    }

    /**
     * POST: 登录
     * @internal param post.username
     * @internal param post.password
     */
    public  function post_login_json(){
        $username = I("post.username",'');
        $password = I('post.password','');

        $result = apiCall(AccountApi::LOGIN,array($username,$password));

        $this->ajaxReturn($result,"json");
    }

}