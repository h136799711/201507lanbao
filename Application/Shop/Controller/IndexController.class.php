<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Shop\Controller;

use Admin\Api\DatatreeApi;
use Shop\Api\BannersApi;
use Shop\Api\ProductApi;
use Shop\Model\ProductModel;

class IndexController extends ShopController{

    public function distributor(){
    	if(IS_GET){
       	 	$this->theme($this->themeType)->display();
		}else{
			dump('dddd');
		}
    }

	protected function _initialize(){
		parent::_initialize();
		$showStartPage = true;
		$last_entry_time = cookie("last_entry_time");
		if(empty($last_entry_time)){
			//一小时过期
			cookie("last_entry_time",time(),3600);
			$last_entry_time = time();			
		}elseif(time() - $last_entry_time < 20*60){
			$showStartPage = false;
		}else{
			//一小时过期
			cookie("last_entry_time",time(),3600);
		}
		
		$this->assign("showstartpage",$showStartPage);
		
	}
	
	/**
	 * 首页
	 */
	public function index(){
		$map= array('uid'=>$this->wxaccount['uid'],'storeid'=>-1,'position'=>C("DATATREE.SHOP_INDEX_BANNERS"));
		
		$page = array('curpage'=>0,'size'=>8);
		$order = "createtime desc";
		$params = false;
		
		$result = apiCall(BannersApi::QUERY,array($map,$page,$order,$params));
//		dump($result);
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("banners",$result['info']['list']);
		
		$map= array('parentid'=>C("DATATREE.STORE_TYPE"));
		$result = apiCall(DatatreeApi::QUERY,array($map,$page,$order,$params));
		
		if(!$result['status']){
			$this->error($result['info']);
		}

		$this->assign("store_types",$result['info']['list']);
		
		// 获取推荐商品
		$result = $this->getProducts();
		if($result['status'] && is_array($result['info'])){
			$this->assign("recommend_products",$result['info']['list']);
		}
		
		$ads  = $this->getAds();
		
		$this->assign("ads",$ads['info']['list']);
		
		//获取推荐店铺
		$result = $this->getRecommendStore();
		
		$this->assign("rec_stores",$result['info']['list']);
		
		//获取首页4格活动
		$result = $this->getFourGrid();
//		
		$this->assign("fourgrid",$result['info']['list']);
//		
		$this->display();
	}

	/**
	 * 获取首页4格活动
	 * 
	 */
	 private function getFourGrid(){
		$page = array('curpage'=>0,'size'=>4);
	 	$map = array('parentid'=>getDatatree("INDEX_4_ACTIVTIY"));
		$order = " sort desc";
		$result = apiCall(DatatreeApi::QUERY, array($map,$page,$order));
	
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	 }
	
	/**
	 * 广告
	 */
	private function getAds(){
		
		$page = array('curpage'=>0,'size'=>2);
		$map = array('position'=>getDatatree("SHOP_INDEX_ADVERT"));
		$result = apiCall(DatatreeApi::QUERY, array($map,$page));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	}
	/**
	 * 推荐店铺
	 */
	private function getRecommendStore(){
		
		$page = array('curpage'=>0,'size'=>4);
		$map = array('position'=>getDatatree("SHOP_INDEX_RECOMMEND_STORE"));
		$result = apiCall(DatatreeApi::QUERY, array($map));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		return $result;
	}
	
	/** 
	 *  
	 */ 
	public function getProducts(){
		
		$page = array('curpage'=>0,'size'=>10);
		$order = "updatetime desc";
		$map = array('onshelf'=>ProductModel::STATUS_ONSHELF);
		$group_id = getDatatree("WXPRODUCTGROUP_RECOMMEND");
		
		$result = apiCall(ProductApi::QUERY_BY_GROUP, array($group_id,$map));
		if(!$result['status']){
			LogRecord($result['info'], __FILE__.__LINE__);	
		}
		
		return $result;
	}
	
}

