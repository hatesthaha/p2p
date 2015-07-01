<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\base;


/**
 * Interface Product
 * @package wanhunet\base
 */
interface Product
{

    /**
     * 通过用户请求生成订单
     * @param Order $order
     * @return mixed
     */
    public function markOrder(Order $order);
}