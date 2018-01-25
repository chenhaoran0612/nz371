<?php

return [
	//父节点
    'nodeArr' => [
        'home' => [
            'url' => '#',
            'i_class' => 'fa fa-th-large fa-fw',
            'name' => '首页',
            'index' => 1
        ],
        

    	'user' => [
    		'url' => '#',
    		'i_class' => 'ti-user fa-fw',
    		'name' => '用户管理',
    		'index' => 30
    	],

    ],


    'subnodeArr' => [

        'home_index' => [
            'url' => '/home',
            'name' => '概况总览',
            'parent_node' => 'home',
            'index' => 10
        ],

        
    
        
        'user_subaccount' => [
            'url' => '/user/subaccount',
            'name' => '子账号管理',
            'parent_node' => 'user',
            'index' => 30
        ],
        'user_usergroup' => [
            'url' => '/user/group',
            'name' => '用户组管理',
            'parent_node' => 'user',
            'index' => 40
        ],
        



    ]


];
