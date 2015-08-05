<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/24
 * Time: 15:54
 */

namespace Api\Controller;



use Bluenow\Api\BicyleDataApi;

class BicyleController extends ApiController{

    //查询月份数据
    public function month(){

        $time = $this->_post('time',0);
        $uid = $this->_post('uid',0);
        $uuid = $this->_post('uuid',0);

        if($time <= 0){
            $this->apiReturnErr("缺失时间参数!");
        }

        if(empty($uid)){
            $this->apiReturnErr("缺失用户ID!");
        }

        if(empty($uuid)){
            $this->apiReturnErr("缺失设备ID!");
        }

        $time = intval($time);

        $year = date('Y',$time);
        $month = date('m',$time);

        $where = array(
            'uid'=>$uid,
            'uuid'=>$uuid,
            'upload_year'=>$year,
            'upload_month'=>$month,
        );

        $list = apiCall(BicyleDataApi::MONTHLY_DATA,array($where));

        if($list['status']){
            $this->apiReturnSuc($list['info']);
        }else{
            $this->apiReturnErr($list['info']);
        }

    }


    public function day(){


        //每天0时
        $time = $this->_post('time',0);
        $uid = $this->_post('uid',0);
        $uuid = $this->_post('uuid',0);
        $time = intval($time);
        if($time <= 0){
            $this->apiReturnErr("缺失时间参数!");
        }

        if(empty($uid)){
            $this->apiReturnErr("缺失用户ID!");
        }

        if(empty($uuid)){
            $this->apiReturnErr("缺失设备ID!");
        }

        $notes = "应用".$this->client_id.":[用户".$uid."],调用动感数据单天获取";

        addLog("Bicyle/day",$_GET,$_POST,$notes);

        $year = date('Y',$time);
        $month = date('n',$time);
        $day = date('j',$time);

        $where = array(
            'uid'=>$uid,
            'uuid'=>$uuid,
            'upload_year'=>$year,
            'upload_month'=>$month,
            'upload_day'=>$day,
        );
        addWeixinLog($where,"BicyleDataApi");

        $count = 0;
        $result = apiCall(BicyleDataApi::COUNT,array($where));
        if($result['status']){
            $count = $result['info'];
        }
        else{
            LogRecord($result['info'],__FILE__.__LINE__);
            $this->apiReturnErr($result['info']);
        }

        addWeixinLog($count,"bicyleData");

        $ret = array(
            'speed'=>0,
            'heart_rate'=>0,
            'distance'=>0,
            'total_distance'=>0,
            'cost_time'=>0,
            'calorie'=>0,
            'upload_time'=>0,
        );

        $size = 500;

        $page = array('curpage'=>0,'size'=>$size);
        $order = " upload_time desc ";

        if($count == 0){
            $this->apiReturnSuc($ret);
        }

        if($count < $size){
            $list = apiCall(BicyleDataApi::QUERY_NO_PAGING,array($where,$order));
            addWeixinLog($list,"bicyleData");
            if($list['status']){
                $list = $list['info'];
            }
            else{
                LogRecord($list['info'],__FILE__.__LINE__);
                $this->apiReturnErr($list['info']);
            }
        }else{
            $list = apiCall(BicyleDataApi::QUERY,array($where,$page,$order));
            if($list['status']){
                $list = $list['info']['list'];
            }else{
                $this->apiReturnErr($list['info']);
            }
        }

        $max = $this->findMax($list);
        if(empty($max)){
            $max = $ret;
        }
        $this->apiReturnSuc($max);

    }

    protected function findMax($list){

        if(empty($list)){
            return null;
        }

        $ret = $list[0];

        foreach($list as $vo){

            if($vo['speed'] == 0 && $vo['heart_rate'] == 0 &&
                $vo['distance'] == 0 && $vo['total_distance'] == 0 &&
                $vo['cost_time'] == 0 && $vo['calorie'] == 0){
                continue;
            }

            if($vo['calorie'] > $ret['calorie'] ){
                $ret = $vo;
            }

        }

        return $ret;

    }

    /**
     * 动感单车数据上报.
     */
    public function add(){


        //TODO: 数据上报
        $uid = $this->_post('uid',0);
        $uuid = $this->_post('uuid','');
        //速度
        $speed = $this->_post('speed',0);
        //速度
        $heart_rate = $this->_post('heart_rate',0);
        //里程
        $distance = $this->_post('distance',0);
        //总程
        $total_distance = $this->_post('total_distance',0);
        //耗费时间
        $cost_time = $this->_post('cost_time',0);
        //卡路里
        $calorie = $this->_post('calorie',0);
        //上报时间
        $upload_time = $this->_post('upload_time',0);

        //目标消耗卡路里
        $target_calorie = $this->_post('target_calorie',0);

        $notes = "应用".$this->client_id.":[用户".$uid."],调用动感数据上报接口";

        addLog("Bicyle/add",$_GET,$_POST,$notes);

        $entity = array(
            'uid'=>$uid,
            'uuid'=>$uuid,
            'speed'=>$speed,
            'heart_rate'=>$heart_rate,
            'distance'=>$distance,
            'total_distance'=>$total_distance,
            'cost_time'=>$cost_time,
            'calorie'=>$calorie,
            'upload_time'=>$upload_time,
            'upload_day'=>date('d',$upload_time),
            'upload_month'=>date('m',$upload_time),
            'upload_year'=>date('Y',$upload_time),
            'target_calorie'=>$target_calorie,
        );

        $result = apiCall(BicyleDataApi::ADD,array($entity));

        if($result['status']){
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr($result['info']);
        }

    }

    function getSupportMethod()
    {
        // TODO: Implement getSupportMethod() method.
    }
}