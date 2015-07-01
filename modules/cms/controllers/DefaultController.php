<?php

namespace modules\cms\controllers;


class DefaultController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
