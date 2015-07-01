<?php


namespace wanhunet\base;


/**
 * Class Application
 * @package wanhunet\base
 * @property \modules\member\models\Member|\modules\asset\behaviors\Asset $member
 * @property \wanhunet\components\llPayComponent $pay
 * @property \wanhunet\base\View $view
 * @property \yii\swiftmailer\Mailer $email
 * @property \callmez\wechat\sdk\Wechat $wechat
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
abstract class Application extends \yii\base\Application
{

    /**
     * @param string $id
     * @param bool $load
     * @return null|\wanhunet\base\Module
     */
    public function getModule($id, $load = true)
    {
        return parent::getModule($id, $load);
    }


}