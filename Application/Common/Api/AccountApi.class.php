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

    function login($username, $password, $email, $phone, $from);

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
//        id,username,email,mobile,status
        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $user_info = $result['info'];

        $result = apiCall(MemberApi::GET_INFO, array(array('id'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $member_info = $result['info'];

        $result = apiCall(WxuserApi::GET_INFO, array(array('id'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $wxuser_info = $result['info'];

        $info = array_merge($user_info,$member_info,$wxuser_info);

        return array('status'=>true,'info'=>$info);
    }

    public function login($username, $password, $email, $phone, $from)
    {
        // TODO: Implement login() method.
        return true;
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

        $username = $entity['username'];
        $password = $entity['password'];
        $email = $entity['email'];
        $mobile = $entity['mobile'];
        $from = $entity['from'];
        M()->startTrans();
        $error = "";
        $flag = false;
        $result = apiCall(UserApi::REGISTER, array($username, $password, $email, $mobile, $from));
        if ($result['status']) {
            $uid = $result['info'];

            $member = array(
                'uid' => $uid,
                'realname' => '',
                'nickname' => '',
                'idnumber' => '',
                'sex' => 0,
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
            //继续注册wxuser表

            $wxuser = array();


        } else {
            $flag = true;
            $error = $result['info'];
        }



        if ($flag) {
            M()->rollback();
            return array('status' => false, 'info' => $error);
        } else {
            M()->commit();
            return array('status' => true, 'info' => $uid);
        }

    }

}