<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/24
 * Time: 16:32
 */

namespace Bluenow\Api;

use Bluenow\Model\BicyleDataModel;
use Common\Api\Api;

class BicyleDataApi extends Api {

    /**
     *
     * 累加里程
     */
    const SUM_DISTANCE = "Bluenow/BicyleData/sum_distance";
    /**
     *
     * 累加时间
     */
    const SUM_TIME = "Bluenow/BicyleData/sum_time";

    /**
     *
     * 累加卡路里
     */
    const SUM_CALORIE = "Bluenow/BicyleData/sum_calorie";

    /**
     *
     * 统计最好的3个成绩
     */
    const BEST_RESULT = "Bluenow/BicyleData/bestResult";
    /**
     *
     * 统计最大
     */
    const MAX = "Bluenow/BicyleData/max";
    /**
     *
     * 每月数据
     */
    const MONTHLY_DATA = "Bluenow/BicyleData/monthlyData";
    /**
     *
     * 统计数目
     */
    const COUNT = "Bluenow/BicyleData/count";
    /**
     *
     * 删除
     */
    const DELETE = "Bluenow/BicyleData/delete";
    /**
     *
     * 新增一条数据，根据ID
     */
    const ADD = "Bluenow/BicyleData/add";
    /**
     *
     * 获取一条数据，根据ID
     */
    const GET_INFO = "Bluenow/BicyleData/getInfo";
    /**
     *
     * 保存，根据ID
     */
    const SAVE_BY_ID = "Bluenow/BicyleData/saveByID";
    /**
     *
     * 保存
     */
    const SAVE = "Bluenow/BicyleData/save";
    /**
     *
     * 分页获取
     */
    const QUERY = "Bluenow/BicyleData/query";
    /**
     *
     * 不分页获取
     */
    const QUERY_NO_PAGING = "Bluenow/BicyleData/queryNoPaging";

    /**
     * 抽象方法，用于设置模型实例
     */
    protected function _init()
    {
        $this->model = new BicyleDataModel();
    }

    public function sum_distance($uid){
        $result = $this->model->query("select sum(sum_max_distance) as sum_max_distance from ( SELECT max(total_distance) as sum_max_distance FROM itboye_bicyle_data where `uid` = ".$uid." group by upload_day,upload_year,upload_month ) as tmp");
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }


    public function sum_time($uid){
        $result = $this->model->query("select sum(sum_max_time) as sum_max_time from ( SELECT max(cost_time) as sum_max_time FROM itboye_bicyle_data where `uid` = ".$uid." group by upload_day,upload_year,upload_month ) as tmp");
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }

    public function sum_calorie($uid){
        $result = $this->model->query("select sum(sum_max_calorie) as sum_max_calorie from ( SELECT max(calorie) as sum_max_calorie FROM itboye_bicyle_data where `uid` = ".$uid." group by upload_day,upload_year,upload_month ) as tmp");
        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }

    public function bestResult($where){
        $result = $this->model->field('max(total_distance) as best_distance,max(calorie) as best_calorie,max(cost_time) as best_cost_time')->where($where)->select();
//        addLog("bestResult",$this->model->getLastSql(),"post", json_encode($where));

        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }

    public function max($where,$field){
        $result = $this->model->where($where)->max($field);

        if($result === false){
            return $this->apiReturnErr($this->model->getDbError());
        }else{
            return $this->apiReturnSuc($result);
        }
    }

    public function monthlyData($where){

        $list = $this->model->field("max(calorie) as max_calorie,upload_day")->where($where)->group("upload_day")->order("upload_day asc")->select();
//        dump($list);
//        exit();
        if($list === false){
           return $this->apiReturnErr($this->model->getDbError());
        }else{
           return $this->apiReturnSuc($list);
        }
    }

}