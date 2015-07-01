<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'email' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
//            'useFileTransport' => true, //放在本地的邮件列表,测试邮件的时候可以开启这个
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sina.com',
                'username' => 'wanhunet@sina.com',
                'password' => 'wanhunet123',
                'port' => '25',
            ],

        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                "text/<alias:\w+>" => "text/view",
            ]
        ],
    ],
    'modules' => [
        'member' => [
            'class' => 'modules\member\Module'
        ],
        'cms' => [
            'class' => 'modules\cms\Module'
        ],
        'asset' => [
            'class' => 'modules\asset\Module'
        ],
        'user' => [
            'class' => 'modules\user\Module'
        ]

    ],
    'language' => 'zh-CN',
    'name' => 'jiuxindai'
];
