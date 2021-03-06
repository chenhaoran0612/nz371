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
        'article' => [
            'url' => '#',
            'i_class' => 'fa fa-file-text-o fa-fw',
            'name' => '文章管理',
            'index' => 10
        ],
    	'user' => [
    		'url' => '#',
    		'i_class' => 'ti-user fa-fw',
    		'name' => '用户管理',
    		'index' => 30
    	],

        'test' => [
            'url' => '#',
            'i_class' => 'fa ti-slice fa-fw',
            'name' => '职业/心理测试',
            'index' => 40
        ],


    ],

    'subnodeArr' => [

        'home_index' => [
            'url' => '/home',
            'name' => '概况总览',
            'parent_node' => 'home',
            'index' => 10
        ],

        'article_category' => [
            'url' => '/article/category',
            'name' => '文章分类管理',
            'parent_node' => 'article',
            'index' => 10
        ],

        'article_index' => [
            'url' => '/article/index',
            'name' => '文章管理',
            'parent_node' => 'article',
            'index' => 20
        ],

        'article_bannerindex' => [
            'url' => '/banner/index',
            'name' => 'Banner管理',
            'parent_node' => 'article',
            'index' => 30
        ],

        'user_subaccount' => [
            'url' => '/user/subaccount',
            'name' => '子账号管理',
            'parent_node' => 'user',
            'index' => 10
        ],

        'user_usergroup' => [
            'url' => '/user/group',
            'name' => '用户组管理',
            'parent_node' => 'user',
            'index' => 20
        ],

        'user_student' => [
            'url' => '/user/student',
            'name' => '学生管理',
            'parent_node' => 'user',
            'index' => 30
        ],

        'test_holandindex' => [
            'url' => '/test/holand/index',
            'name' => '霍兰德兴趣测试',
            'parent_node' => 'test',
            'index' => 10
        ],
        'test_holandreport' => [
            'url' => '/test/holand/report',
            'name' => '霍兰德测试报告',
            'parent_node' => 'test',
            'index' => 20
        ],

        'test_holandadmin' => [
            'url' => '/test/holand/admin',
            'name' => '霍兰德测试管理',
            'parent_node' => 'test',
            'index' => 30
        ],

        'test_mbtiindex' => [
            'url' => '/test/mbti/index',
            'name' => 'MBTI测试',
            'parent_node' => 'test',
            'index' => 40
        ],
        'test_mbtireport' => [
            'url' => '/test/mbti/report',
            'name' => 'MBTI测试报告',
            'parent_node' => 'test',
            'index' => 50
        ],

        'test_mbtiadmin' => [
            'url' => '/test/mbti/admin',
            'name' => 'MBTI测试报告管理',
            'parent_node' => 'test',
            'index' => 60
        ],

    ]


];
