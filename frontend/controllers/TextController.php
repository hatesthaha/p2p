<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace frontend\controllers;


use modules\cms\models\Post;
use modules\member\models\MemberOther;
use wanhunet\base\Controller;
use wanhunet\helpers\Utils;
use wanhunet\wanhunet;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class TextController extends Controller
{
    public $layout = 'layout';

    public function actionView($alias = null)
    {
        $functionName = 'ac' . ucfirst($alias);
        if (!method_exists($this, $functionName)) {
            $post = Post::findByAlias($alias);
            if ($alias == null) {
                throw new NotFoundHttpException();
            } elseif ($post === null) {
                throw new NotFoundHttpException();
            } else {
                try {
                    return $this->view($alias, [
                        'post' => $post
                    ]);
                } catch (\Exception $e) {
                    return $this->view('text', [
                        'post' => $post
                    ]);
                }
            }
        } else {
            return call_user_func_array([$this, $functionName], []);
        }

    }

    public function acProblem()
    {
        $cid = '16';
        $posts = Post::find()->where(['category_id' => $cid])->limit(10)->orderBy('id desc')->all();
        return $this->view('problem', [
            'posts' => $posts
        ]);
    }

    public function acShare()
    {
        $errorAuth = '';
        if (wanhunet::$app->user->isGuest) {
            Utils::ensureOpenId();
            $url = wanhunet::$app->urlManager->createAbsoluteUrl(['share/index']);
            $errorAuth = '您未绑定微信账号';
            if (
                ($openId = wanhunet::$app->request->get('open_id')) !== null
                &&
                ($model = MemberOther::findOne(['row' => $openId, 'table' => MemberOther::TABLE_WECHAT])) !== null
            ) {
                /** @var MemberOther $model */
                if (\Yii::$app->user->login($model->member, 3600 * 24 * 30)) {
                    $url = wanhunet::$app->urlManager->createAbsoluteUrl(['share/index/' . wanhunet::app()->member->invitation]);
                    $errorAuth = '';
                }
            }
        } else {
            $url = wanhunet::$app->urlManager->createAbsoluteUrl(['share/index/' . wanhunet::app()->member->invitation]);
        }
        $url = wanhunet::app()->wechat->getOauth2AuthorizeUrl($url, 'authorize', 'snsapi_userinfo');
        if (wanhunet::$app->request->get('qrcode', null) !== null) {
            Utils::qrcode(urldecode(wanhunet::$app->request->get('qrcode', urlencode($url))));
        }
        return $this->view('share', [
            'url' => $url,
            'errorAuth' => $errorAuth
        ]);
    }
}