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
        'subaccount' => [
            '用户管理' => [
                'user_subaccount' => '子账号管理',
                'user_usergroup' => '用户组管理',
                
            ]
        ],
    ],

    //栏目关联权限
    'relation_permission' => [
        'user_subaccount' => 'user_subaccountcreate,user_subaccountedit',
    ],
    //公共权限
    'public_permission' => [
        'home_index'
    ]
];
