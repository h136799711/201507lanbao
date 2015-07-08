<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/3
 * Time: 09:29
 */

namespace Common\Api;

use Admin\Api\MemberApi;
use Uclient\Api\UserApi;
use Weixin\Api\WxuserApi;

interface IAccount
{

    function login($username, $password,$type='1',$from='');

    function register($entity);
    //根据用户ID获取信息
    function getInfo($id);
}

/**
 * 本系统账号相关操作统一接口
 * Class AccountApi
 * @package Common\Api
 */
class AccountApi implements IAccount
{

    /**
     * 登录
     */
    const LOGIN = "Common/Account/login";
    /**
     * 注册
     */
    const REGISTER = "Common/Account/register";
    /**
     * 获取用户信息
     */
    const GET_INFO = "Common/Account/getInfo";

    public function getInfo($id){

        $result = apiCall(UserApi::GET_INFO, array($id));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $user_info = $result['info'];
        if($user_info['status'] != 1){
            return array('status'=>true,'info'=>"用户不存在或被禁用!");
        }

        $result = apiCall(MemberApi::GET_INFO, array(array('id'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $member_info = $result['info'];

//        $result = apiCall(WxuserApi::GET_INFO, array(array('id'=>$id)));
//
//        if(!$result['status']){
//            return array('status' => false, 'info' => $result['info']);
//        }
//
//        $wxuser_info = $result['info'];

        $info = array_merge($user_info,$member_info);
        unset($info['status']);
        unset($info['id']);
        unset($info['login']);
        unset($info['reg_ip']);
        unset($info['reg_time']);
        unset($info['qq']);
        unset($info['score']);
        unset($info['last_login_ip']);


        return array('status'=>true,'info'=>$info);
    }

    /**
     * 登录接口
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param int|string $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @param string $from
     * @return int 登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password,$type='1',$from='')
    {

        $result = apiCall(UserApi::LOGIN,array($username,$password,$type));
        $notes = "[用户".$username.",类型：".$type."],调用登录接口";
        addLog("/Account/login","","",$notes);
        return $result;
    }

    /**
     *
     * @param $entity | key＝》username,password ,from . email,mobile非必须
     * @return array
     */
    public function register($entity)
    {

        if (!isset($entity['username']) || !isset($entity['password']) || !isset($entity['from'])) {
            return array('status' => false, 'info' => "账户信息缺失!");
        }

        $empty_check = array('nickname','avatar','province','country','city');
        foreach($empty_check as $vo){
            if(!isset($wxuser[$vo])){
                $wxuser[$vo] = '';
            }
        }

        $username = $entity['username'];
        $password = $entity['password'];
        $email = $entity['email'];
        $mobile = $entity['mobile'];
        $from = $entity['from'];

        $trans = M();
        $trans->startTrans();
        $error = "";
        $flag = false;
        $result = apiCall(UserApi::REGISTER, array($username, $password, $email, $mobile, $from));
        $uid = 0;
        if ($result['status']) {
            $uid = $result['info'];

            $member = array(
                'uid' => $uid,
                'realname' => '',
                'nickname' => '',
                'idnumber' => '',
                'sex' =>  $wxuser['sex'],
                'birthday' => time(),
                'qq' => '',
                'score' => 0,
                'login' => 0,
            );

            $result = apiCall(MemberApi::ADD, array($member));
            if (!$result['status']) {
                $flag = true;
                $error = $result['info'];
            }


        } else {
            $flag = true;
            $error = $result['info'];
        }


        if ($flag) {
            apiCall(UserApi::DELETE_BY_ID, array($uid));
            $trans->rollback();
            return array('status' => false, 'info' => $error);
        } else {
            $trans->commit();
            return array('status' => true, 'info' => $uid);
        }


    }

}