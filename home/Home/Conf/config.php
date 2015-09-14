<?php
return array(
//'配置项'=>'配置值'
"DB_HOST"=>'localhost',
"DB_NAME"=>'itservice',
"DB_PWD"=>'cuug',
"DB_TYPE"=>'mysql',
"DB_USER"=>'root',
"DB_PORT"=>3360,
'MODULE_ALLOW_LIST'=>array('Admin','Home'),
'DEFAULT_MODULE'=> 'Home', 
//开启静态缓存
'HTML_CHACHE_ON'=>true,
//全局缓存过期时间
'HTML_CHACHE_SUFFIX'=>'.html',
//缓存的规则
'HTML_CHACHE_RULES'=>array(
'USER:servicePhone'=>'1111',)
);

