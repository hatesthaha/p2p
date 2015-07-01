<?php
namespace backend\controllers;

use wanhunet\base\Controller;
use yii\web\UploadedFile;

/**
 * 后台默认控制器，用于用户权限操作，不涉及RBAC
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->renderPartial('index');
    }


    public function actionUpload()
    {
        $siteRoot = str_replace('\\', '/', realpath(dirname(dirname(dirname(__FILE__))) . '/')) . "/frontend/web/upload/";
        if (!empty($_FILES)) {

            $tempPath = $_FILES['file']['tmp_name'];

            $filesName = uniqid() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $uploadPath = $siteRoot . $filesName;

            move_uploaded_file($tempPath, $uploadPath);

            $answer = array('newname' => $filesName,'oldname'=>$_FILES['file']['name']);
            $json = json_encode($answer);

            echo $json;

        } else {

            echo 'No files';

        }
    }

    public function actionNihao()
    {
    }
}
