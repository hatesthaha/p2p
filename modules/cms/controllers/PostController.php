<?php

namespace modules\cms\controllers;

use wanhunet\wanhunet;
use Yii;
use modules\cms\models\post;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


/**
 * Class PostController
 * @package modules\cms\controllers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class PostController extends BackendController
{

    /**
     * Lists all post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $posts = Post::find();
        if (wanhunet::$app->request->get("category", "") !== "") {

            $posts = $posts->where(['status' => Post::STATUS_ACTIVE, "category_id" => wanhunet::$app->request->get("category")]);
        }
        //$pages = new Pagination(['totalCount' => $posts->count(), 'pageSize' => '10']);
        //$model = $posts->offset($pages->offset)->limit($pages->limit)->all();
        $model = $posts->where(['status' => Post::STATUS_ACTIVE])->orderBy('id desc')->all();
        foreach ($model as $k => $v) {
            $model[$k]['created_at'] = date("Y-m-d ", $v['created_at']);
            $model[$k]['updated_at'] = date("Y-m-d ", $v['updated_at']);
        }
        return [
            'model' => $model,
            //'pages' => $pages,
        ];
    }

    /**
     * Displays a single post model.
     * @return mixed
     */
    public function actionView()
    {
        $id = wanhunet::$app->request->post('id');
        return $this->findModel($id);
    }

    /**
     * Creates a new post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new post();
        $request = wanhunet::$app->request;
        $model->setAttributes($request->post());
        if ($model->validate(wanhunet::$app->request->post()) && $model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }

    /**
     * Updates an existing post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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

    /**
     * 删除控制器
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {

        $request = wanhunet::$app->request;
        return Post::updateAll(['status' => Post::STATUS_DELETED], ['id' => $request->post("id")]);
    }

    /**
     * Finds the post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = post::findOne($id)) !== null) {
            /** @var Post $model */
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
