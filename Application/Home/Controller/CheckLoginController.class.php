<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Home\Controller;
use Think\Controller;

class CheckLoginController extends  HomeController {
	
	//主题
	protected $theme = "default";
	
	protected function _initialize() {
		parent::_initialize();		
		//TODO: 检测用户是否登录
		
		if($this->is_login()){
//			define("HOME_UID",session(""))
			
		}else{
			//TODO: 跳转到登录页面
		}
		
	}
	
	private function is_login(){
		
		
		
	}

	
}