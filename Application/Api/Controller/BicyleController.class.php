<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/24
 * Time: 15:54
 */

namespace Api\Controller;



use Admin\Api\MemberApi;
use Bluenow\Api\BicyleDataApi;
use Common\Api\AccountApi;
use Uclient\Api\UserApi;

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
//            $this->apiReturnErr("缺失设备ID!");
        }

        $time = intval($time);

        $year = date('Y',$time);
        $month = date('m',$time);

        $where = array(
            'uid'=>$uid,
//            'uuid'=>$uuid,
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
//            $this->apiReturnErr("没有数据!");
        }

        $notes = "应用".$this->client_id.":[用户".$uid."],调用动感数据单天获取";

        addLog("Bicyle/day",$_GET,$_POST,$notes);

        $year = date('Y',$time);
        $month = date('n',$time);
        $day = date('j',$time);

        $where = array(
            'uid'=>$uid,
//            'uuid'=>$uuid,
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
//            'speed'=>0,
//            'heart_rate'=>0,
//            'distance'=>0,
//            'total_distance'=>0,
//            'cost_time'=>0,
//            'calorie'=>0,
//            'upload_time'=>0,
//            'target_'
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


        if($uid == 0){
            $this->apiReturnErr("缺失UID参数!");
        }
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
        if($upload_time == 0){
            $upload_time = time();
        }
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
            $this->update_continuous_day($uid);
            $this->apiReturnSuc($result['info']);
        }else{
            $this->apiReturnErr($result['info']);
        }

    }

    public function bestResult(){

        $uid = $this->_post('uid',0);

        if($uid == 0){
            $this->apiReturnErr("缺失UID参数!");
        }

        $notes = "应用".$this->client_id.":[用户".$uid."],调用个人最好成绩";

        addLog("Bicyle/bestResult",$_GET,$_POST,$notes);
        $entity = array(
            'uid'=>$uid,
        );

        $result = apiCall(BicyleDataApi::BEST_RESULT,array($entity));

        if($result['status']){
            $data = $result['info'];
            if(isset($data['best_cost_time']) && empty($data['best_cost_time'])){
                $data['best_cost_time'] = 0;
            }
            if(isset($data['best_calorie']) && empty($data['best_calorie'])){
                $data['best_calorie'] = 0;
            }
            if(isset($data['best_distance']) && empty($data['best_distance'])){
                $data['best_distance'] = 0;
            }
            $this->apiReturnSuc($data);
        }else{
            LogRecord($result['info'],__FILE__.__LINE__);
            $this->apiReturnErr($result['info']);
        }
    }

    public function total(){
        $uid = $this->_post('uid',0);

        if($uid == 0){
            $this->apiReturnErr("缺失UID参数!");
        }

        $notes = "应用".$this->client_id.":[用户".$uid."],调用个人总成绩";

        addLog("Bicyle/total",$_GET,$_POST,$notes);
        //TODO: 缓存600秒
        S("Bicyle_total_result",null);
        $result_data = S("Bicyle_total_result".$uid);
        if($result_data == null){
            $result_data = array();

            $result = apiCall(BicyleDataApi::SUM_CALORIE,array($uid));
            if($result['status']){
                $result_data['sum_max_calorie'] = $result['info'][0]['sum_max_calorie'];
            }else{
                $this->apiReturnErr($result['info']);
            }

            $result = apiCall(BicyleDataApi::SUM_DISTANCE,array($uid));

            if($result['status']){
                $result_data['sum_max_distance'] = $result['info'][0]['sum_max_distance'];
//                array_push($result_data,$result['info'][0]);
            }else{
                $this->apiReturnErr($result['info']);
            }
            $result = apiCall(BicyleDataApi::SUM_TIME,array($uid));

            if($result['status']){
                $result_data['sum_max_time'] = $result['info'][0]['sum_max_time'];
//                array_push($result_data,$result['info'][0]);
            }else{
                $this->apiReturnErr($result['info']);
            }

            S('Bicyle_total_result'.$uid,$result_data,300);
        }
        $this->apiReturnSuc($result_data);

    }

    function getSupportMethod()
    {
        // TODO: Implement getSupportMethod() method.
    }

    /**
     * 更新连续运动天数
     * 时间点:
     * 1. 上报数据的时候
     * @param $uid
     */
    private function update_continuous_day($uid){
        $notes = "应用".$this->client_id.":[用户".$uid."],更新连续运动天数";

        addLog("Bicyle/update_continuous_day",$_GET,$_POST,$notes);
        $yester_year = date('Y',time()-24*3600);
        $yester_month = date('m',time()-24*3600);
        $yester_day = date('d',time()-24*3600);
        $year = date('Y',time());
        $month = date('m',time());
        $day = date('d',time());

        $map = array(
            'upload_year'=>$year,
            'upload_month'=>$month,
            'upload_day'=>$day,
        );

        $result = apiCall(BicyleDataApi::COUNT,array($map));
//        echo "count今天";
//        dump($result);
        if($result['status']){
            $cnt = $result['info'];
            if($cnt > 0){
                //已经处理过
                return;
            }
        }else{
            LogRecord($result['info'],__FILE__.__LINE__);
            $this->apiReturnErr($result['info']);
        }

        $map = array(
            'upload_year'=>$yester_year,
            'upload_month'=>$yester_month,
            'upload_day'=>$yester_day,
        );

        $result = apiCall(BicyleDataApi::COUNT,array($map));

        $dont_sports = false;

        if($result['status']){
            $cnt = $result['info'];
            if($cnt == 0){
                //昨天没运动
                $dont_sports = true;
            }
        }else{
            LogRecord($result['info'],__FILE__.__LINE__);
            $this->apiReturnErr($result['info']);
        }

        $entity = array(
            'continuous_day'=>1,
        );


        if($dont_sports){
            //重置连续天数
            $result = apiCall(MemberApi::SAVE,array(array('uid'=>$uid),$entity));
            if(!$result['status']){
                LogRecord($result['info'],__FILE__.__LINE__);
                $this->apiReturnErr($result['info']);
            }
        }else{

            $result = apiCall(AccountApi::SET_CONTINUOUS_DAY_INC,array($uid));
            if(!$result['status']){
                LogRecord($result['info'],__FILE__.__LINE__);
                $this->apiReturnErr($result['info']);
            }
        }

    }
}