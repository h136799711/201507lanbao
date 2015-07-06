<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/2
 * Time: 14:22
 */

namespace Shop\Api;


use Common\Api\Api;
use Shop\Model\WithdrawModel;

class WithdrawApi extends Api{

    /**
     * 查询，不分页
     */
    const QUERY_NO_PAGING = "Shop/Withdraw/queryNoPaging";
    /**
     * 查询，分页
     */
    const QUERY = "Shop/Withdraw/query";
    /**
     * 添加
     */
    const ADD = "Shop/Withdraw/add";
    /**
     * 保存
     */
    const SAVE = "Shop/Withdraw/save";
    /**
     * 获取信息
     */
    const GET_INFO = "Shop/Withdraw/getInfo";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Shop/Withdraw/saveByID";

    /**
     * 删除
     */
    const DELETE = "Shop/Withdraw/delete";

    protected function _init(){
        $this->model = new WithdrawModel();
    }

}