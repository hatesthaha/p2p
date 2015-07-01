<?php

namespace modules\member\controllers;


class DefaultController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
