<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\member\event;


use yii\base\Event;

class Verification extends Event
{
    public $type;
    public $field;
    public $created_at;
}