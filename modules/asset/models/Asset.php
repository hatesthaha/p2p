<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\asset\models;


use common\models\Config;
use modules\member\models\Member;
use wanhunet\db\ActiveRecord;
use wanhunet\helpers\ErrorCode;
use wanhunet\helpers\Utils;
use yii\base\ErrorException;
use yii\db\IntegrityException;
use yii\web\HttpException;

/**
 * This is the model class for table "wh_asset".
 *
 * @property integer $user_id
 * @property string $pay_pass
 * @property integer $money
 * @property integer $experience_money_max
 * @property integer $experience_money_max_inc
 * @property string $bank_card
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \modules\member\models\Member $user
 */
class Asset extends ActiveRecord
{
    const EVENT_BEFORE_SAVE_MONEY = "beforeSaveMoney";
    const EVENT_AFTER_SAVE_MONEY = "afterSaveMoney";
    const EVENT_BEFORE_SAVE_EM = 'beforeSaveExperienceMoney';
    const EVENT_AFTER_SAVE_EM = 'beforeSaveExperienceMoney';
    const EVENT_BEFORE_SAVE_EM_MAX = 'beforeSaveExperienceMoneyMax';
    const EVENT_AFTER_SAVE_EM_MAX = 'beforeSaveExperienceMoneyMax';

    private $_experienceMoney = [];
    private $_experienceMoneyMax = [];
    private $_money = [];

    public function behaviors()
    {
        return Utils::behaviorMerge(self::className(), parent::behaviors());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%asset}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_card'], 'string'],
            [['pay_pass'], 'string', 'max' => 255],
        ];
    }

    /**
     * 获取用户信息
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Member::className(), ['id' => 'user_id']);
    }


    /**
     * 保存金钱
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws HttpException
     */
    public function saveMoney($runValidation = true, $attributeNames = null)
    {
        $this->trigger(self::EVENT_BEFORE_SAVE_MONEY);

        foreach ($this->_money as $money) {
            /** @var AssetMoney $money */
            try {
                $money->save();
            } catch (\Exception $e) {
                throw new HttpException(500, "程序错误");
                break;
            }
        }

        $save = parent::save($runValidation, $attributeNames);

        $this->trigger(self::EVENT_AFTER_SAVE_MONEY);
        return $save;
    }

    /**
     * 增加账户余额并不保存
     * @param int $step
     */
    public function setIncMoney($step = 0)
    {
        $assetMoney = new AssetMoney();
        $assetMoney->user_id = $this->user_id;
        $assetMoney->status = AssetMoney::STATUS_INC;
        $assetMoney->step = $step;
        $assetMoney->type = AssetMoney::TYPE_MONEY;
        $this->_money[] = $assetMoney;
    }

    public function setIncNewMoney($step)
    {
        $assetMoney = new AssetMoney();
        $assetMoney->user_id = $this->user_id;
        $assetMoney->status = AssetMoney::STATUS_MONEY_UNPAYED;
        $assetMoney->step = $step;
        $assetMoney->type = AssetMoney::TYPE_MONEY;
        $assetMoney->save();
        return $assetMoney;
    }

    public function setIncMoneyPayed($id)
    {
        /** @var AssetMoney $assetMoney */
        $assetMoney = AssetMoney::find()->where(['id' => $id, 'status' => AssetMoney::STATUS_MONEY_UNPAYED])->one();
        if ($assetMoney !== null) {
            $this->money = $this->money + $assetMoney->step;
            $assetMoney->status = AssetMoney::STATUS_INC;
            $assetMoney->save();
            $this->save();
        }
    }


    /**
     * 减少账户余额并不保存
     * @param int $step
     * @param string $info
     * @throws ErrorException
     */
    public function setDecMoney($step = 0, $info = null)
    {
        if (($this->money - $step) < 0) {
            throw new ErrorException("账号余额不能为负值", ErrorCode::Asset_money_minus);
        } else {
            $assetMoney = new AssetMoney();
            $assetMoney->user_id = $this->user_id;
            $assetMoney->status = AssetMoney::STATUS_DEC;
            $assetMoney->step = $step;
            $assetMoney->type = AssetMoney::TYPE_MONEY;
            if (!is_null($info)) {
                $assetMoney->llinfo = json_encode([$info]);
            }
            $this->_money[] = $assetMoney;

            $this->money = $this->money - $step;
        }
    }


    /**
     * 增加体验金
     * @param int $step
     * @param null|string $action
     * @param null|int $actionUid
     */
    public function setIncExperienceMoney($step = 0, $action = null, $actionUid = null)
    {
        $assetMoney = new AssetMoney();
        $assetMoney->user_id = $this->user_id;
        $assetMoney->status = AssetMoney::STATUS_INC;
        $assetMoney->step = $step;
        $assetMoney->type = AssetMoney::TYPE_EXPERIENCE_MONEY;
        if ($action !== null) {
            $assetMoney->action = $action;
        }
        if ($actionUid !== null) {
            $assetMoney->action_uid = $actionUid;
        }
        $this->_experienceMoney[] = $assetMoney;
    }

    public function setIncExperienceMoneyInc($step = 0)
    {
        $this->experience_money_max_inc += $step;
        $incMax = Config::getInstance()->getProperty('setIncMax.Inc');
        if ($this->experience_money_max_inc > $incMax) {
            $time = (int)($this->experience_money_max_inc / $incMax);
            $this->experience_money_max_inc = ($this->experience_money_max_inc % $incMax);
            $this->setIncExperienceMoneyMax($incMax * $time);
            return $this->saveExperienceMoneyMax();
        }
        return $this->update();
    }

    /**
     * 减少体验金
     * @param int $step
     * @throws ErrorException
     */
    public function setDecExperienceMoney($step = 0)
    {
        if (($this->getExperienceMoney() - $step) < 0) {
            throw new ErrorException("账号体验金余额不能为负值", ErrorCode::Asset_money_minus);
        } else {
            $assetMoney = new AssetMoney();
            $assetMoney->user_id = $this->user_id;
            $assetMoney->status = AssetMoney::STATUS_DEC;
            $assetMoney->step = $step;
            $assetMoney->type = AssetMoney::TYPE_EXPERIENCE_MONEY;

            $this->_experienceMoney[] = $assetMoney;
        }
    }

    /**
     * 保存体验金
     */
    public function saveExperienceMoney()
    {
        $this->trigger(self::EVENT_BEFORE_SAVE_EM);

        foreach ($this->_experienceMoney as $em) {
            /** @var AssetMoney $em */
            try {
                $em->save();
            } catch (\Exception $e) {
                throw new HttpException(500, "程序错误");
                break;
            }
        }

        $this->trigger(self::EVENT_AFTER_SAVE_EM);
    }


    /**
     * 增加个人体验金投资上限
     * @param $step
     */
    public function setIncExperienceMoneyMax($step)
    {
        $assetMoney = new AssetMoney();
        $assetMoney->user_id = $this->user_id;
        $assetMoney->status = AssetMoney::STATUS_INC;
        $assetMoney->step = $step;
        $assetMoney->type = AssetMoney::TYPE_EXPERIENCE_MONEY_MAX;
        $this->_experienceMoneyMax[] = $assetMoney;

        $this->experience_money_max = $this->experience_money_max + $step;
    }


    /**
     * 保存最高体验金
     * @return bool
     * @throws HttpException
     */
    public function saveExperienceMoneyMax()
    {
        $this->trigger(self::EVENT_BEFORE_SAVE_EM_MAX);

        foreach ($this->_experienceMoneyMax as $money) {
            /** @var AssetMoney $money */
            try {
                $money->save();
            } catch (\Exception $e) {
                throw new HttpException(500, "程序错误");
                break;
            }
        }
        $save = $this->save();
        $this->trigger(self::EVENT_AFTER_SAVE_EM_MAX);
        return $save;
    }

    /**
     * @return int
     */
    public function getExperienceMoney()
    {
        return AssetMoney::getExperienceMoney();
    }

    /**
     * @return array|AssetMoney[]
     */
    public function getExperienceMoneys()
    {
        return AssetMoney::getExperienceMoneys();
    }


}