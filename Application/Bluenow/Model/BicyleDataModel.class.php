<?php
/**
 * Created by PhpStorm.
 * User: hebidu
 * Date: 15/7/24
 * Time: 16:00
 */

namespace Bluenow\Model;
use Think\Model;

class BicyleDataModel extends Model{

    protected $_auto = array(
        array('create_time',"time",self::MODEL_INSERT,"function"),
    );

}