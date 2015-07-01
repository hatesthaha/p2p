<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\controllers;


use modules\invest\models\Post;
use wanhunet\wanhunet;
use yii\web\NotFoundHttpException;

class ActivityController extends BackendController
{
    public function actionIndex()
    {
        $model = Post::find()->orderBy('id desc')->all();
        foreach ($model as $k => $v) {
            $model[$k]['created_at'] = date("Y-m-d h:i:s", $v['created_at']);
            $model[$k]['updated_at'] = date("Y-m-d h:i:s", $v['updated_at']);
            $model[$k]['status'] = Post::get_record_status($v['status']);
        }
        return $model;
    }


    public function actionView()
    {
        $id = wanhunet::$app->request->post('id');
        return $this->findModel($id);
    }


    public function actionCreate()
    {
        $model = new Post();
        $request = wanhunet::$app->request;
        $model->setAttributes($request->post(), false);
        if ($model->validate(wanhunet::$app->request->post()) && $model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }


    public function actionUpdate()
    {
        $id = wanhunet::$app->request->post('id');
        $model = $this->findModel($id);
        $model->setAttributes(wanhunet::$app->request->post(), false);

        if ($model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }


    public function actionDelete()
    {

        $request = wanhunet::$app->request;
        return Post::deleteAll(['id' => $request->post("id")]);
    }

    public function actionCheck()
    {
        $request = wanhunet::$app->request;
        return Post::deleteAll(['status' => Post::STATUS_ACTIVE], ['id' => $request->post("id")]);
    }

    public function actionUncheck()
    {
        $request = wanhunet::$app->request;
        return Post::deleteAll(['status' => Post::STATUS_DELETED], ['id' => $request->post("id")]);
    }


    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            /** @var Post $model */
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}