<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Admin\Api;

use \Common\Api\Api;
use \Admin\Model\ActionLogModel;

class ActionLogApi extends Api{


    /**
     * 查询，不分页
     */
    const QUERY_NO_PAGING = "Admin/ActionLog/queryNoPaging";
    /**
     * 添加
     */
    const ADD = "Admin/ActionLog/add";
    /**
     * 保存
     */
    const SAVE = "Admin/ActionLog/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Admin/ActionLog/saveByID";

    /**
     * 删除
     */
    const DELETE = "Admin/ActionLog/delete";

    /**
     * 查询
     */
    const QUERY = "Admin/ActionLog/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Admin/ActionLog/getInfo";

	protected function _init(){
		$this->model = new ActionLogModel();
	}
	
}