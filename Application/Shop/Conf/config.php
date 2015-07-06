<?php
return array(
	//'配置项'=>'配置值',
	'TMPL_PARSE_STRING'  =>array(
     	'__CDN__' => __ROOT__.'/Public/cdn', // 更改默认的/Public 替换规则
		'__JS__'     => __ROOT__.'/Public/'.MODULE_NAME.'/js', // 增加新的JS类库路径替换规则
     	'__CSS__'     => __ROOT__.'/Public/'.MODULE_NAME.'/css', // 增加新的JS类库路径替换规则
     	'__IMG__'     => __ROOT__.'/Public/'.MODULE_NAME.'/imgs', // 增加新的JS类库路径替换规则	
     
	),	
	'DEFAULT_THEME'=>'default',
	'SESSION_PREFIX'=>'Shop_',
    
     //TODO: 一套系统对应一个公众号的商城
    'STORE_ID'=>5,
    
	'SHOW_PAGE_TRACE'=>false,
	
);