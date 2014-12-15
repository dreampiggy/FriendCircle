<?php
return array(

	'URL_MODEL'				=>	2,
	'URL_CASE_INSENSITIVE'	=>	true,
    'URL_HTML_SUFFIX'       =>  '', //新加的，默认后缀是html
    // 'URL_ROUTE_ON'          =>  true,
    // 'URL_ROUTE_RULES'       =>  array(
    // ),
	'DB_TYPE'		 =>	'mysql',
    'DB_HOST'		 =>	'localhost',
    'DB_NAME'		 =>	'myfrienddb',
    'DB_USER'		 =>	'root',
    'DB_PWD'		 =>	'123',
    'DB_PORT'		 =>	'8889',
    'DB_PREFIX'		 =>	'',
    'DB_FIELDS_CACHE'=> false, //调试用

    'SHOW_PAGE_TRACE' =>false, //显示页面调试明细
    
    'TMPL_PARSE_STRING' 	=>	array(
    	'__JS__'	=>	'/FriendCircle/Public/js',
        '__CSS__'   =>  '/FriendCircle/Public/css',
        '__IMG__'   =>  '/FriendCircle/Public/css/IMG',
        
        '__info__' =>   'http://localhost:81/FriendCircle/Index/info', 
        '__friends__'   =>  'http://localhost:81/FriendCircle/Index/friends',
        '__logout__'    =>  'http://localhost:81/FriendCircle/User/logout',
        '__Friend__'    =>  'http://localhost:81/FriendCircle/Friend',
        '__index__'     =>  'http://localhost:81/FriendCircle',
    ),

    'SESSION_AUTO_START'	=>	true,
);