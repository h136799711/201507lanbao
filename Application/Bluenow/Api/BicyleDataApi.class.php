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