<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/2
 * Time: 14:20
 */

namespace Shop\Api;


use Common\Api\Api;
use Shop\Model\WalletModel;

class WalletApi extends Api{

    protected function _init(){
        $this->model = new WalletModel();
    }

}