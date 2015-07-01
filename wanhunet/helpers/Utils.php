<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace wanhunet\helpers;


use wanhunet\base\Module;
use wanhunet\phpqrcode\includes;
use wanhunet\phpqrcode\QRcode;
use wanhunet\wanhunet;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Util
 * @package wanhunet\helpers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class Utils
{
    /**
     * 获取命名空间为 wanhunet\Module 的模块
     * @return array 获取命名空间为 wanhunet\Module 的模块
     */
    public static function getUserModules()
    {
        $modules = [];
        foreach ((wanhunet::$app->getModules()) as $moduleName => $module) {
            if (is_array($module)) {
                if (isset($module['class'])) {
                    $module = wanhunet::$app->getModule($moduleName);
                    if ($module instanceof Module) {
                        $modules[$moduleName] = $module::className();
                    }
                }
            } elseif (!is_array($module) && is_object($module)) {
                if ($module instanceof Module) {
                    $modules[$moduleName] = $module::className();
                }
            }
        }
        return $modules;
    }

    /**
     * @return array
     */
    public static function getUserModulesOfMenus()
    {
        $menusTree = [];
        foreach (Utils::getUserModules() as $moduleName => $module) {
            $menusTree[] = wanhunet::app()->getModule($moduleName)->getMenus();
        }
        return $menusTree;
    }

    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    public static function xml_encode($data, $root = 'root', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id 数字索引key转换为的属性名
     * @return string
     */
    public static function data_to_xml($data, $item = 'item', $id = 'id')
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? self::data_to_xml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }

    /**
     * 合并行为用于在params中的配置
     * @param $className
     * @param $parentBehaviors
     * @return array
     */
    public static function behaviorMerge($className, $parentBehaviors)
    {
        $behavior = isset(wanhunet::$app->params['behaviors'][$className]) ? wanhunet::$app->params['behaviors'][$className] : [];
        return ArrayHelper::merge($parentBehaviors, $behavior);
    }

    /**
     * 合并行为用于在params中的配置
     * @param $className
     * @param $parentEvents
     * @return array
     */
    public static function eventMerge($className, $parentEvents)
    {
        $behavior = isset(wanhunet::$app->params['events'][$className]) ? wanhunet::$app->params['events'][$className] : [];
        return ArrayHelper::merge($parentEvents, $behavior);
    }

    /**
     * @param $idcard
     * @param $idcard_name
     * @return bool
     */
    public static function idcardVerify($idcard, $idcard_name)
    {
        $idcard_name = trim($idcard_name);
        $idcard = trim($idcard);
        if (empty($idcard) || empty($idcard_name)) {
            return '请填写所有内容';
        }

        $vStr = $idcard;
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );

        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return '身份证格式错误';

        if (!in_array(substr($vStr, 0, 2), $vCity)) return '身份证格式错误';

        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);

        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return '身份证格式错误';
        if ($vLength == 18) {
            $vSum = 0;

            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }

            if ($vSum % 11 != 1) return '身份证格式错误';
        }

        return null;
    }

    /**
     * @param $validate
     * @return bool
     */
    public static function phoneVerify($validate)
    {
        return preg_match("/^1[34578]\d{9}$/", $validate);
    }

    /**
     * @return string
     */
    public static function csrfField()
    {
        return Html::hiddenInput(wanhunet::$app->request->csrfParam, wanhunet::$app->request->getCsrfToken(), ['id' => 'csrf']);
    }

    /**
     * @param $destmobile
     * @param $msgText
     * @return int
     */
    public static function sendSMS($destmobile, $msgText)
    {
        $data = [
            'account' => 'sdk_jxd',
            'password' => '13488811',
            'destmobile' => $destmobile,
            'msgText' => $msgText . '【玖信贷】'
        ];
        $data = http_build_query($data);
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type:application/x-www-form-urlencoded; charset=UTF-8",
                'content' => $data
            )
        );
        $context = stream_context_create($opts);
        $html = file_get_contents('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/http/sendBatchMessage', false, $context);
        return $html;
        /*//TODO
        Utils::sendEmail('435690026@qq.com', 'titile', '手机号:' . $destmobile . '内容' . $msgText);
        return 1523;*/
    }

    /**
     * @param $to
     * @param $subject
     * @param $body
     * @return \yii\mail\MessageInterface
     */
    public static function sendEmail($to, $subject, $body)
    {
        //TODO
//        $body = $to . '|' . $body;
//        $to = '435690026@qq.com';
        $email = wanhunet::app()->email->compose();
        $email->setFrom('wanhunet@sina.com')
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();
        return $email;
    }

    public static function timeCut($enddate, $startdate)
    {
        $t12 = abs($startdate - $enddate);
        $start = 0;
        $string = "";
        $y = floor($t12 / (3600 * 24 * 360));
        if ($start || $y) {
            $start = 1;
            $t12 -= $y * 3600 * 24 * 360;
            $string .= $y . "年";
        }
        $m = floor($t12 / (3600 * 24 * 31));
        if ($start || $m) {
            $start = 1;
            $t12 -= $m * 3600 * 24 * 31;
            $string .= $m . "月";
        }
        $d = floor($t12 / (3600 * 24));
        if ($start || $d) {
            $start = 1;
            $t12 -= $d * 3600 * 24;
            $string .= $d . "天";
        }
        $h = floor($t12 / (3600));
        if ($start || $h) {
            $start = 1;
            $t12 -= $h * 3600;
            $string .= $h . "时";
        }
        $s = floor($t12 / (60));
        if ($start || $s) {
            $start = 1;
            $t12 -= $s * 60;
            $string .= $s . "分";
        }
        $string .= "{$t12}秒";
        return $string;
    }

    public static function dateFormat($format, $time)
    {
        return !empty($time) ? date($format, $time) : "";
    }

    public static function moneyFormat($money)
    {
        return sprintf("%.2f", $money);
    }

    public static function moneyShortFormat($money)
    {
        if (($re = intval($money / 10000)) > 0) {
            return $ret = [$re, '万'];
        } elseif (($re = intval($money / 1000)) > 0) {
            return $ret = [$re, '千'];
        } else {
            return $ret = [$money, "元"];
        }
    }

    public static function ensureOpenId()
    {
        $wechat = wanhunet::app()->wechat;
        $request = wanhunet::$app->request;
        if (
            $request->post('open_id') == null
            &&
            $request->get('open_id') == null
        ) {
            $result = $wechat->getOauth2AccessToken($request->get('code'));
            $_GET['open_id'] = $result['openid'];
        }
    }

    /**
     * 导出数据为excel表格
     * @param array $data 一个二维数组,结构如同从数据库查出来的数组
     * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
     * @param string $filename 下载的文件名
     * @examlpe
     * $stu = M ('User');
     * $arr = $stu -> select();
     * exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
     */
    public static function exportExcel($data = array(), $title = array(), $filename = 'report')
    {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=" . $filename . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        if (!empty($title)) {
            foreach ($title as $k => $v) {
                $title[$k] = iconv("UTF-8", "GB2312", $v);
            }
            $title = implode("\t", $title);
            echo "$title\n";
        }
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck] = iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key] = implode("\t", $data[$key]);

            }
            echo implode("\n", $data);
        }
    }

    public static function qrcode($data)
    {
        includes::includes();
        // 纠错级别：L、M、Q、H
        $level = 'L';
        // 点的大小：1到10,用于手机端4就可以了
        $size = 4;
        // 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
        //$path = "images/";
        // 生成的文件名
        //$fileName = $path.$size.'.png';
        QRcode::png($data, false, $level, $size);
        die;
    }

}