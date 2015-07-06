<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->ajaxReturn(array('error'=>'DENY ACCESS!'),"xml");
    }
}