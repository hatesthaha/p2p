<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\components;


use modules\asset\models\BankCard;

/**
 * Interface AssetBehavior
 * 如果增加用户钱包
 * @package wanhunet\components
 */
interface AssetBehavior {
    /**
     * 获取银行卡数组信息
     * @param string $cardId
     * @return array|\modules\asset\models\BankCard
     */
    public function getBankCard($cardId = null);

    /**
     * 删除一个银行卡
     * @param BankCard $backCard
     */
    public function removeBankCard(BankCard $backCard);

    /**
     * 添加或者银行卡
     * @param BankCard $backCard
     */
    public function setBankCard(BankCard $backCard);

    /**
     * 保存银行卡到数据库bank_card字段 每个银行卡是serialize 全部银行卡是 json_encode
     * @return bool
     */
    public function saveBankCard();

    /**
     * 获取支付密码散列
     * @return string
     */
    public function getPayPass();

    /**
     * 用于验证支付密码
     * @param $payPass
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePayPass($payPass);

    /**
     * 设置支付密码，仅是设置支付密码并未没有保存的
     * @param $payPass
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function setPayPass($payPass);

    /**
     * 设置并保存密码
     * @param $payPass
     * @return bool
     */
    public function savePayPass($payPass);


    /**
     * 获取账户余额
     * @return int
     */
    public function getMoney();

    /**
     * 直接设置账户余额
     * @param int $money
     */
    public function setMoney($money);

    /**
     * 增加账户余额并不保存
     * @param int $step
     */
    public function setIncMoney($step = 0);


    /**
     * 减少账户余额并不保存
     * @param int $step
     */
    public function setDecMoney($step = 0);


    /**
     * 设置并保存账号余额
     * @param null $money
     * @return bool
     */
    public function saveMoney($money = null);


    /**
     * @return int
     */
    public function getExperienceMoney();

    /**
     * 设置体验金
     * @param int $experienceMoney
     */
    public function setExperienceMoney($experienceMoney);

    /**
     * 增加体验金
     * @param int $step
     */
    public function setIncExperienceMoney($step = 0);

    /**
     * 减少体验金
     * @param int $step
     */
    public function setDecExperienceMoney($step = 0);

    /**
     * 设置并保存体验金
     * @param int $experienceMoney
     */
    public function saveExperienceMoney($experienceMoney = null);

    /**
     * 确认支付密码不为空，请在控制器中处理此异常
     */
    public function ensurePayPass();
}