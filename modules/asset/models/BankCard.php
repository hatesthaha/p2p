<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\models;


use wanhunet\base\Model;
use yii\base\Arrayable;


/**
 * Class BankCard
 * @package modules\asset\models
 * @property string $cardId
 * @property string $bankName
 * @property string $cardUserName
 * @property string $cardPhone
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class BankCard extends Model
{
    private $_cardId, $_bankName, $_cardUserName, $_cardPhone;

    public function rules()
    {
        return [
            [['cardId', 'cardPhone'], 'integer'],
            [['cardId', 'cardPhone', 'bankName', 'cardUserName'], 'required'],
        ];
    }


    /**
     * @return mixed
     */
    public function getCardId()
    {
        return $this->_cardId;
    }

    /**
     * @param mixed $cardId
     */
    public function setCardId($cardId)
    {
        $this->_cardId = $cardId;
    }

    /**
     * @return mixed
     */
    public function getBankName()
    {
        return $this->_bankName;
    }

    /**
     * @param mixed $bankName
     */
    public function setBankName($bankName)
    {
        $this->_bankName = $bankName;
    }


    /**
     * @return mixed
     */
    public function getCardUserName()
    {
        return $this->_cardUserName;
    }

    /**
     * @param mixed $cardUserName
     */
    public function setCardUserName($cardUserName)
    {
        $this->_cardUserName = $cardUserName;
    }

    /**
     * @return mixed
     */
    public function getCardPhone()
    {
        return $this->_cardPhone;
    }

    /**
     * @param mixed $CardPhone
     */
    public function setCardPhone($CardPhone)
    {
        $this->_cardPhone = $CardPhone;
    }

    function __sleep()
    {
        return ['_cardId', '_bankName', '_cardUserName', '_cardPhone'];
    }


    function __toString()
    {
        return json_encode([
            $this->_cardId, $this->_bankName, $this->_cardUserName, $this->_cardPhone
        ]);
    }


}