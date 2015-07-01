<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\base;

use yii\base\Event;


/**
 * Interface Order
 * @package wanhunet\base
 */
interface Order
{
    const NAME_ORDER_EXPIRE = 'orderExpire';
    const STATUS_PAYED = 20;
    const STATUS_UNPAYED = 10;

    const STATUS_ORDER_MADE = 110;
    const STATUS_ORDER_FIRST_TRIAL = 120;
    const STATUS_ORDER_TRIAL = 130;

    /**
     * 获取订单ID
     * @return mixed
     */
    public function getOrderId();

    /**
     * 获取订单总额
     * @return mixed
     */
    public function getPrice();

    /**
     * 获取订单类型
     * @return mixed
     */
    public function getType();

    /**
     * 获取订单状态
     * @return mixed
     */
    public function getStatus();

    /**
     * 获取订单其他信息
     * @return mixed
     */
    public function getParams();


    /**
     * 设置用户ID
     * @param int $id
     * @return mixed
     */
    public function setUserId($id);

    public function getUserId();

    /**
     * 设置产品ID
     * @param int $id
     * @return mixed
     */
    public function setProductId($id);

    /**
     * 保存订单
     */
    public function saveOrder();

    public function finishPay(Event $event);

    /**
     * @param $notify_url
     * @param $return_url
     */
    public function setUrl($notify_url, $return_url);

    /**
     * notify_url return_url
     * @return array
     */
    public function getUrl();

    public function getName();

    public function getInfo();

    public static function findByIdNo($id);
}