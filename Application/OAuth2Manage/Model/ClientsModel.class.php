<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/6
 * Time: 17:17
 */

namespace OAuth2Manage\Model;

use Think\Model;

class ClientsModel extends Model{



    protected $tablePrefix = "oauth_";

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array(
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
    );


}

