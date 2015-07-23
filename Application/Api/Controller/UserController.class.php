<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 14:22
 */
namespace Api\Controller;

use Common\Api\AccountApi;
use Uclient\Model\OAuth2TypeModel;

class UserController extends ApiController{

    protected  $allowType = array("json","rss","html");

    function getSupportMethod()
    {
        return array(
            'item'=>array(
                'param'=>'access_token|username|password',
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
    public  function login_post(){

        $username = $this->_post("username");

        $password = $this->_post("password");

        $result = apiCall(AccountApi::LOGIN,array($username,$password));

        if($result['status']){
            $uid = $result['info'];
            $result = apiCall(AccountApi::GET_INFO,array($uid));

            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr($result['info']);
        }

    }

    /**
     * POST: 注册
     */
    public function register(){

        if(IS_POST){

            $username = $this->_post("username");
            $password = $this->_post("password");
            $from = OAuth2TypeModel::SELF;

            $entity = array(
                'username'=>$username,
                'password'=>$password,
                'from'=>$from,
            );

            $result = apiCall(AccountApi::REGISTER,array($entity));

            if($result['status']){
                $this->apiReturnSuc($result['info']);
            }else{
                $this->apiReturnErr($result['info']);
            }
        }else{
            $this->apiReturnErr("只支持POST请求!");
        }

    }

    /**
     * 用户信息更新
     */
    public function update(){
        if(IS_POST){
            $sex = $this->_post('sex',0);
            $nickname= $this->_post('nickname','');
            $signature = $this->_post("signature",'');
            $birthday = $this->_post('birthday',date("Y-m-d",time()));
            $height = $this->_post('height',0);
            $weight = $this->_post('weight',0);
            $target_weight = $this->_post('target_weight',0);

            $uid = $this->_post('uid',0);
            $entity = array(
                'nickname'=>$nickname,
                'height'=>$height,
                'weight'=>$weight,
                'sex'=>$sex,
                'target_weight'=>$target_weight,
                'birthday'=>$birthday,
                'signature'=>$signature,
            );
            $result = apiCall(AccountApi::UPDATE,array($uid,$entity));

            if($result['status']){
                $this->apiReturnSuc("操作成功！");
            }else{
                $this->apiReturnErr($result['info']);
            }
        }
    }

    public function avatar_post(){

        //TODO: 上传头像

    }

    /**
     *
     */
//    public function checkEmail(){
//
//    }

}