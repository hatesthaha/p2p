<?php
use \modules\asset\behaviors\ExperienceMoney;

return [
    'adminEmail' => 'admin@example.com',
    'behaviors' => [
        /** 验证手机和邮箱行为 */
        \modules\member\models\VerificationCode::className() => [
            'assetExperienceMoney' => [
                'class' => ExperienceMoney::className()
            ],
        ],
        /** 用户行为 */
        \modules\member\models\Member::className() => [
            'asset' => [
                'class' => \modules\asset\behaviors\Asset::className(),
                'assetConfig' => \modules\asset\models\Asset::className(),
            ],
            'assetExperienceMoney' => [
                'class' => ExperienceMoney::className()
            ],

        ],
        /** 钱包行为 */
        \modules\asset\models\Asset::className() => [
            'asset_log' => [
                'class' => \modules\asset\behaviors\Log::className()
            ],
            'assetExperienceMoney' => [
                'class' => ExperienceMoney::className()
            ]
        ],
        /** 订单行为 */
        \modules\invest\models\InvestList::className() => [
            'payedFinish' => \modules\invest\behaviors\InvestListPayed::className()
        ],
        /** 钱包记录行为 */
        \modules\asset\models\AssetMoney::className() => [
            \modules\asset\behaviors\Log::className(),
        ],
        /** 用户其他信息行为 */
        \modules\member\models\MemberOther::className() => [
            ExperienceMoney::className()
        ]
    ],
    'events' => [
        /** 用户金钱行为事件 */
        \modules\asset\behaviors\Asset::className() => [
            \modules\member\models\Member::EVENT_AFTER_INSERT => \modules\asset\behaviors\Asset::FUNCTION_MEMBER_AFTER_INSERT, //用户注册后创建他的钱包
        ],
        /** 体验金行为事件 */
        \modules\asset\behaviors\ExperienceMoney::className() => [
            \modules\member\models\VerificationCode::EVENT_VERIFY_SUCCESS_EMAIL => ExperienceMoney::FUNCTION_INC_EMAIL,//认证邮箱领取
            \modules\member\models\VerificationCode::EVENT_VERIFY_SUCCESS_PHONE => ExperienceMoney::FUNCTION_INC_PHONE,//认证手机
            \modules\asset\models\AssetMoney::EVENT_FINISH_PAY => ExperienceMoney::FUNCTION_INC_FIRST_MONEY,//首次充钱
            \modules\member\models\Member::EVENT_BEFORE_SAVE_IDCARD => ExperienceMoney::FUNCTION_INC_FIRST_IDCARD,//首次验证身份证
            \modules\member\models\MemberOther::EVENT_AFTER_INSERT => ExperienceMoney::FUNCTION_INT_BANGDING,   //绑定其他信息
        ],
        /** 支付完成之后行为事件 */
        \modules\invest\behaviors\InvestListPayed::className() => [
            \modules\invest\models\InvestList::EVENT_BEFORE_FINISH_PAY => \modules\invest\behaviors\InvestListPayed::FUNCTION_SET_INTEREST, //支付订单完成之后前置事件
            \modules\invest\models\InvestList::EVENT_AFTER_FINISH_PAY => \modules\invest\behaviors\InvestListPayed::FUNCTION_SET_INTEREST_M, //支付订单完成之后后置事件
        ],
        /** Log事件 */
        \modules\asset\behaviors\Log::className() => [
            \modules\asset\models\AssetMoney::EVENT_BEFORE_INSERT => \modules\asset\behaviors\Log::FUNCTION_BEFORE_INSERT
        ],
    ]
];
