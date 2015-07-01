<?php

namespace modules\cms\controllers;

use wanhunet\wanhunet;
use modules\cms\models\Category;
use yii\web\NotFoundHttpException;


/**
 * Class CategoryController
 * @package modules\cms\controllers
 * @author: zhouzhongyuan <435690026@qq.com>
 * @copyright wanhunet
 * @link http://www.wanhunet.com
 */
class CategoryController extends BackendController
{
    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        return Category::eachCategoryTree();
    }

    /**
     * Displays a single Category model.
     * @return mixed
     */
    public function actionView()
    {
        $id = wanhunet::$app->request->post('id');
        return $this->findModel($id);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $model->setAttributes(wanhunet::$app->request->post(), false);
        if ($model->save()) {
            return true;
        } else {
            return current($model->getFirstErrors());
        }
    }

    /**
     * Updates an existing Category model.
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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = wanhunet::$app->request;
        return Category::updateAll(['status' => Category::STATUS_DELETED], ['id' => $request->post("id")]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
