<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/31
 * Time: 14:23
 */

namespace Api\Controller;


use Think\Controller;

class EmptyController extends Controller{

    public function index(){
        $this->ajaxReturn(array('code'=>404,'data'=>'找不到此资源！'),"json");
    }

}