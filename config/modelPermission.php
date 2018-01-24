<?php

return [

    /*
    |--------------------------------------------------------------------------
    | NORMAL_PERMISSION
    |--------------------------------------------------------------------------
    |	新建用户初始化权限列表
    |
    |
    |
    */

    'normal_permission' =>[

        'distributor' => [
            '账户管理' => [
                'account_distributor' => '我的账户',
                'account_distributortransactionlog' => '交易详情',
            ],
            '系统管理' => [
                'system_manage' => '系统信息设置',
            ],
        ],

        'vendor' => [
            '商品管理' => [
                'goods_goodsbasic' => '商品列表',
            ],
            '账户管理' => [
                'account_vendor' => '我的账户',
                'account_vendortransactionlog' => '交易详情',
            ],
            '系统管理' => [
                'system_manage' => '系统信息设置',
            ],
        ],

        'subaccount' => [
            '商品管理' => [
                'goods_goodscategory' => '商品分类',
                'goods_goodsonline' => '商品列表',
            ],
            '账户管理' => [
                'account_paymentmethod' => '账户配置',
                'account_accountuser' => '账户列表',
                'account_usertransactionlog' => '交易详情',
            ],
            '用户管理' => [
                'user_distributor' => '经销商管理',
                'user_vendor' => '供应商管理',
                'user_subaccount' => '子账号管理',
                'user_usergroup' => '用户组管理',
                
            ],
            '系统管理' => [
                'system_manage' => '系统信息设置',
            ],
        ],
    ],

    //栏目关联权限
    'relation_permission' => [
        'user_distributor' => 'user_distributorcreate,user_distributoredit,user_distributordownload,user_distributorupload',
        'user_vendor' => 'user_vendorcreate,user_vendoredit',
        'user_subaccount' => 'user_subaccountcreate,user_subaccountedit',
        'user_usertransactionlog' => 'user_recharge,user_distributortransactionlog',

        'account_paymentmethod' => 'account_paymentmethodsetting,account_paymentmethodcreate,account_paymentmethodsave',
        'account_vendor' => 'account_onlinepay,account_recharge',
        'account_distributor' => 'account_onlinepay,account_recharge',
        'goods_goodsbasic' => 'goods_goodsbasiccreate,goods_goodsbasicedit',
        'goods_goodsonline' => 'goods_goodsonliesave,goods_goodsonlineedit',
        'goods_goodscategory' => 'goods_goodscategorycreate,goods_goodscategoryedit',
    ],
    //公共权限
    'public_permission' => [
        'home_index'
    ]
];
