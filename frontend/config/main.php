<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'modules\member\models\Member', //数据表的原因
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'member' => function () {
            return \modules\member\models\Member::findIdentity(\wanhunet\wanhunet::$app->user->getId());
        },
        'view' => [
            'class' => 'wanhunet\base\View'
        ],
        'pay' => [
            'class' => 'wanhunet\components\llPayComponent',
            'llpay_config' => [
                'oid_partner' => '201408071000001543',
                'key' => '201408071000001543test_20140812',
                'valid_order' => '10080',
            ],
            'risk_item' => '{\"user_info_bind_phone\":\"13958069593\",\"user_info_dt_register\":\"20131030122130\",\"risk_state\":\"1\",\"frms_ware_category\":\"1009\"}',
            'busi_partner' => '101001',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                "text/<alias:\w+>" => "text/view",
                "share/index/<invitation:\w+>" => "share/index",
            ]
        ],
        /*'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx32814d588c44c17c',
            'appSecret' => '23b0f218574551f18db1dc991dbee87f',
            'token' => 'mtlxny1410483192'
        ]*/
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx747621d124836f6f',
            'appSecret' => '6d245164dc7079e70abf7a4ad008b482',
            'token' => 'mtlxny1410483192'
        ]
    ],
    'params' => $params,
];
