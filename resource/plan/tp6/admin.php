<?php
//php builder --plan=tp6.admin --output=apiadmin --data=lqCloud
return [
    'plan'      =>'tp6',
    //输出 路径
    'outputPath'=>'%sdist/%s/',
    //生成器
    'generator' =>[
        'base'     =>[
            //命名空间
            'namespace'        =>'app\\',
            //源代码 路径
            'sourcePath'       =>'src/',
            'composerName'     =>'tp/apiadmin',
            'projectErrInitNum'=>13900,
            'errorNumIncrease' =>100
        ],
        'project'  =>[
            'platform'=>[
                'account'       =>'Account',
                'account_token'       =>'AccountToken',
                'account_type'       =>'AccountType',
                'agency'=>'Agency',
                'agency_level'=>'AgencyLevel',
                'agency_sales'=>'AgencySales',
                'agency_type'=>'AgencyType',
                'company'=>'Company',
                'company_account'=>'CompanyAccount',
                'company_department'=>'CompanyDepartment',
                'company_level'=>'CompanyLevel',
                'company_role'=>'CompanyRole',
                'company_tag'=>'CompanyTag',
                'company_type'=>'CompanyType',
                'employee'=>'Employee',
                'employee_friend'=>'EmployeeFriend',
                'industry'=>'Industry',
                'industry_group'=>'IndustryGroup',
                'maker'=>'Maker',
                'maker_tag'=>'MakerTag',
                'maker_union'=>'MakerUnion',
                'maker_union_member'=>'MakerUnionMember',
                'member'=>'Member',
                'platform'=>'Platform',
                'platform_account'=>'PlatformAccount',
                'platform_agency'=>'PlatformAgency',
                'platform_company'=>'PlatformCompany',
                'platform_maker'=>'PlatformMaker',
                'platform_member'=>'PlatformMember',
                'tag'=>'Tag',
                //'tag_ref_type'=>'TagRefType'
            ],
        ],
        'templates'=>[
            //--------------生成项目相关代码----------------
            //生成Controller
            'admin/Controller.php'=>[
                'path'               =>'application/admin/controller/%s.php',
                'generate_by_project'=>true,
                'base_class'         =>'Base',
                'suffix'             =>'Controller',
            ],
            //生成Validate
            'Validate.php'         =>[
                'path'               =>'application/validate/%sValidate.php',
                'generate_by_project'=>true,
                'use_module'         =>0,
            ],
            //生成Model
            'Model.php'         =>[
                'path'               =>'application/Models/%s.php',
                'generate_by_project'=>true,
                'use_module'         =>0,
            ],
            //生成路由
            'routes.php'         =>[
                'path'               =>'application/route.php',
                'generate_by_project'=>true,
                'use_module'         =>0,
                'is_append'=>true
            ],
            'views/admin_list.vue.php'=>[
                'path'=>'vue/src/view/%s/%s.vue',
                'generate_by_project'=>true,
                'use_module'=>1,
                'use_table_name'=>1,
            ],
            'views/admin_style.less.php'=>[
                'path'=>'vue/src/view/%s/%s.less',
                'generate_by_project'=>true,
                'use_module'=>1,
                'use_table_name'=>1,
            ],
            'views/admin_api.js.php'=>[
                'path'=>'vue/src/api/%s.js',
                'generate_by_project'=>true,
                'use_module'=>false,
                'use_table_name'=>1,
            ],
        ],
    ],
];