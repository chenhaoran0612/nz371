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
        'goods' => [
            'url' => '#',
            'i_class' => 'fa fa-gift fa-fw',
            'name' => '商品管理',
            'index' => 10
        ],

        'account' => [
            'url' => '#',
            'i_class' => 'ti-credit-card fa-fw',
            'name' => '账户管理',
            'index' => 20
        ],
    	'user' => [
    		'url' => '#',
    		'i_class' => 'ti-user fa-fw',
    		'name' => '用户管理',
    		'index' => 30
    	],

        'system' => [
            'url' => '#',
            'i_class' => 'ti-settings fa-fw',
            'name' => '系统管理',
            'index' => 200
        ],

    ],


    'subnodeArr' => [

        'home_index' => [
            'url' => '/home',
            'name' => '概况总览',
            'parent_node' => 'home',
            'index' => 10
        ],

        //供应商商品列表
        'goods_goodsbasic' => [
            'url' => '/goods/basic',
            'name' => '商品列表',
            'parent_node' => 'goods',
            'index' => 10
        ],
        //管理员商品分类
        'goods_goodscategory' => [
            'url' => '/goods/category',
            'name' => '商品分类',
            'parent_node' => 'goods',
            'index' => 20
        ],
        //管理员商品列表
        'goods_goodsonline' => [
            'url' => '/goods/online',
            'name' => '商品列表',
            'parent_node' => 'goods',
            'index' => 30
        ],


        'account_paymentmethod' => [
            'url' => '/account/paymentmethod',
            'name' => '账户配置',
            'parent_node' => 'account',
            'index' => 10
        ],
        // 管理员账户
        'account_accountuser' => [
            'url' => '/account/user',
            'name' => '账户列表',
            'parent_node' => 'account',
            'index' => 20
        ],
        // 经销商账户
        'account_distributor' => [
            'url' => '/account/distributor',
            'name' => '我的账户',
            'parent_node' => 'account',
            'index' => 30
        ],
        // 供应商账户
        'account_vendor' => [
            'url' => '/account/vendor',
            'name' => '我的账户',
            'parent_node' => 'account',
            'index' => 40
        ],
        // 经销商交易详情
        'account_distributortransactionlog' => [
            'url' => '/distributor/transaction/log',
            'name' => '交易详情',
            'parent_node' => 'account',
            'index' => 50
        ],
        // 供应商交易详情
        'account_vendortransactionlog' => [
            'url' => '/vendor/transaction/log',
            'name' => '交易详情',
            'parent_node' => 'account',
            'index' => 60
        ],
        // 供应商交易详情
        'account_usertransactionlog' => [
            'url' => '/user/transaction/log',
            'name' => '交易详情',
            'parent_node' => 'account',
            'index' => 70
        ],



        'user_distributor' => [
            'url' => '/user/distributor',
            'name' => '经销商管理',
            'parent_node' => 'user',
            'index' => 10
        ],
        'user_vendor' => [
            'url' => '/user/vendor',
            'name' => '供应商管理',
            'parent_node' => 'user',
            'index' => 20
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
        'user_paymentmethod' => [
            'url' => '/user/paymentmethod',
            'name' => '账户管理',
            'parent_node' => 'user',
            'index' => 40
        ],
        
        'system_manage' => [
            'url' => '/system/manage',
            'name' => '系统基础信息',
            'parent_node' => 'system',
            'index' => 10
        ],

        'system_distributor' => [
            'url' => '/system/distributor',
            'name' => '系统基础信息',
            'parent_node' => 'system',
            'index' => 20
        ],

        'system_vendor' => [
            'url' => '/system/vendor',
            'name' => '系统基础信息',
            'parent_node' => 'system',
            'index' => 30
        ],



    ]


];
