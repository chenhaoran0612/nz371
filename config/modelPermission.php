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
                'user_student' => '学生管理',
            ],
            '文章管理' => [
                'article_category' => '文章分类管理',
                'article_index' => '文章管理',
                'article_bannerindex' => 'Banner管理'
            ],
            '测试管理' => [
                'test_holandadmin' => '霍兰德测试管理',
            ],
        ],

        'student' => [
            '职业/心理测试' => [
                'test_holandindex' => '霍兰德职业倾向测试',
                'test_holandreport' => '霍兰德测试报告'
            ],
            
        ],

    ],

    //栏目关联权限
    'relation_permission' => [
        'user_subaccount' => 'user_subaccountcreate,user_subaccountedit',
        'user_student' => 'user_studentcreate,user_studentedit',
        'article_category' => 'article_categorycreate,article_categoryedit',
        'article_index' => 'article_create,article_edit',
        'article_bannerindex' => 'article_bannercreate',
        'test_holandreport' => 'test_holandreportdetail',
        'test_holandadmin' => 'test_holandadmindetail'
    ],
    //公共权限
    'public_permission' => [
        'home_index'
    ]
];
