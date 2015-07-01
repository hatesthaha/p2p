<?php
/**
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright 万虎网络
 * @link http://www.wanhunet.com
 */

namespace modules\invest\controllers;


use modules\invest\models\Invest;
use wanhunet\wanhunet;
use yii\web\NotFoundHttpException;

class ProductController extends BackendController
{

    public function actionIndex()
    {
        $model = Invest::find()->orderBy('id desc')->all();
        foreach ($model as $k => $v) {
            $model[$k]['created_at'] = date("Y-m-d H:i:s", $v['created_at']);
            $model[$k]['updated_at'] = date("Y-m-d H:i:s", $v['updated_at']);
            $model[$k]['invest_status'] = Invest::get_record_status($v['invest_status']);
            $model[$k]['buy_time_start'] = date("Y-m-d H:i:s", $v['buy_time_start']);
            $model[$k]['buy_time_end'] = date("Y-m-d H:i:s", $v['buy_time_end']);
            $model[$k]['type'] = Invest::get_type($model[$k]['type']);
        }
        return $model;
    }


    public function actionView()
    {
        $id = wanhunet::$app->request->post('id');
        $rs = $this->findModel($id)->toArray();
        $rs['imgs'] = json_decode($rs['imgs'], true);
        return $rs;
    }


    public function actionCreate()
    {
        $model = new Invest();
        $request = wanhunet::$app->request;
        $post = $request->post();

        $post['buy_time_start'] = isset($post['buy_time_start']) ? strtotime($post['buy_time_start']) : time();
        $post['buy_time_end'] = isset($post['buy_time_end']) ? strtotime($post['buy_time_end']) : time();
        $post['imgs'] = json_encode($post['img']);

        unset($post['img']);

        $model->setAttributes($post, false);
        if ($model->validate($post) && $model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }


    public function actionUpdate()
    {
        $id = wanhunet::$app->request->post('id');
        $model = $this->findModel($id);
        $request = wanhunet::$app->request;
        $post = $request->post();
        $post['buy_time_start'] = strtotime($post['buy_time_start']);
        $post['buy_time_end'] = strtotime($post['buy_time_end']);

        $post['imgs'] = json_encode($post['img']);
        unset($post['img']);

        $model->setAttributes($post, false);

        if ($model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }


    public function actionDelete()
    {
        $request = wanhunet::$app->request;
        return Invest::deleteAll(['id' => $request->post("id")]);
    }

    public function actionCheck()
    {
        $request = wanhunet::$app->request;
        return Invest::updateAll(['invest_status' => Invest::STATUS_ACTIVE], ['id' => $request->post("id")]);
    }

    public function actionUncheck()
    {
        $request = wanhunet::$app->request;
        return Invest::updateAll(['invest_status' => Invest::STATUS_DELETED], ['id' => $request->post("id")]);
    }


    protected function findModel($id)
    {
        if (($model = Invest::findOne($id)) !== null) {
            /** @var Invest $model */
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}