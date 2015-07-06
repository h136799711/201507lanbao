<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016 杭州博也网络科技, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Weixin\Api;
use Common\Api\Api;
use Common\Model\WxuserFamilyModel;

class WxuserFamilyApi extends  Api{



    /**
     * 查询，不分页
     */
    const QUERY_NO_PAGING = "Weixin/WxuserFamily/queryNoPaging";
    /**
     * 添加
     */
    const ADD = "Weixin/WxuserFamily/add";
    /**
     * 保存
     */
    const SAVE = "Weixin/WxuserFamily/save";
    /**
     * 保存根据ID主键
     */
    const SAVE_BY_ID = "Weixin/WxuserFamily/saveByID";

    /**
     * 删除
     */
    const DELETE = "Weixin/WxuserFamily/delete";

    /**
     * 查询
     */
    const QUERY = "Weixin/WxuserFamily/query";
    /**
     * 查询一条数据
     */
    const GET_INFO = "Weixin/WxuserFamily/getInfo";
    /**
     * 如果不存在记录则插入一条
     */
    const CREATE_ONE_IF_NONE = "Weixin/WxuserFamily/createOneIfNone";


	protected function _init(){
		$this->model = new WxuserFamilyModel();
	}

    /**
     * 根据参数创建一个wxuserfamily记录
     *
     * @param $wxaccount_id
     * @param $openid
     * @return array|bool
     */
	public function createOneIfNone($wxaccount_id,$openid){
		
		$wxuserfamily = $this->model->where(array('wxaccount_id'=>$wxaccount_id,'openid'=>$openid))->find();
		if($wxuserfamily === false ){
			$error = $this->model->getDbError();
			return $this -> apiReturnErr($error);
		}elseif(is_array($wxuserfamily)){
			//已存在
			return $this -> apiReturnSuc($wxuserfamily['id']);
		}
		
		$entity = array(
			'wxaccount_id'=>$wxaccount_id,
			'openid'=>$openid,
		);
		
		
		return  $this->add($entity);
	}
	
}
