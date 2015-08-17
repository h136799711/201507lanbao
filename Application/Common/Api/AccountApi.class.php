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

/**
 * 本系统账号相关操作统一接口
 * Class AccountApi
 * @package Common\Api
 */
class AccountApi
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
    /**
     * 更新用户信息
     */
    const UPDATE = "Common/Account/update";
    /**
     * 连续运动天数加1
     */
    const SET_CONTINUOUS_DAY_INC = "Common/Account/setContinuousDayInc";

    /**
     * 连续运动天数加1
     * @param $uid
     * @return mixed
     */
    public function setContinuousDayInc($uid){
        $result = apiCall(MemberApi::SET_INC,array(array('uid'=>$uid),'continuous_day',1));
        return $result;
    }

    public function update($id,$entity){

        $update_entity = array(
            'nickname'=>$entity['nickname'],
            'height'=>$entity['height'],
            'weight'=>$entity['weight'],
            'sex'=>$entity['sex'],
            'target_weight'=>$entity['target_weight'],
            'birthday'=>$entity['birthday'],
            'signature'=>$entity['signature'],
            'avatar_id'=>$entity['avatar_id'],
        );

        $result = apiCall(MemberApi::SAVE,array(array('uid'=>$id),$update_entity));

        return $result;
    }

    public function getInfo($id){

        $result = apiCall(UserApi::GET_INFO, array($id));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $user_info = $result['info'];
        if($user_info['status'] != 1){
            return array('status'=>true,'info'=>"用户不存在或被禁用!");
        }

        $result = apiCall(MemberApi::GET_INFO, array(array('uid'=>$id)));

        if(!$result['status']){
            return array('status' => false, 'info' => $result['info']);
        }

        $member_info = $result['info'];

        $info = array_merge($user_info,$member_info);

        $info['uid'] = $info['id'];
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

        $empty_check = array('nickname','avatar','province','country','city','sex');
        foreach($empty_check as $vo){
            if(!isset($wxuser[$vo])){
                $entity[$vo] = '';
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
                'sex' =>  0,
                'birthday' => date('Y-m-d',time()),
                'qq' => '',
                'score' => 0,
                'login' => 0,
                'height'=>160,
                'weight'=>50,
                'target_weight'=>45,
                'signature'=>'',
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