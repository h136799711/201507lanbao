<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */


return  array(
    'LOG_TYPE'              =>  'Db',
    'LOG_RECORD'            =>  true,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  =>  true,    // 是否记录异常信息日志
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',//,WARN,NOTIC,INFO,DEBUG,SQL',  // 允许记录的日志级别
    'DB_FIELDS_CACHE'       =>  false, // 字段缓存信息
    'DB_SQL_LOG'            =>  true, // 记录SQL信息
    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
    'TMPL_STRIP_SPACE'      =>  false,       // 是否去除模板文件里面的html空格与换行
    'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
    'URL_CASE_INSENSITIVE'  =>  false,  // URL区分大小写
    'SHOW_PAGE_TRACE'           =>  false, //显示调试信息
     // 数据库配置
    'TMPL_CONTENT_TYPE'     =>  'text/html', // 默认模板输出类型
    'TMPL_ACTION_ERROR'     =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/think_exception.tpl',// 异常页面的模板文件

//  'DB_TYPE'                   =>  'mysql',
//  'DB_HOST'                   =>  '127.0.0.1',//rdsrrbifmrrbifm.mysql.rds.aliyuncs.com
//  'DB_NAME'                   =>  'boye_2015_05_26_10_15_13', //boye_ceping
//  'DB_USER'                   =>  'root',//boye
//  'DB_PWD'                    =>  '1',//bo-ye2015BO-YE
//  'DB_PORT'                   =>  '3306',
//  'DB_PREFIX'                 =>  'itboye_',
);