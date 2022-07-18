<?php

namespace app\controllers;

use app\models\Document;
use yii\web\Controller;

class DocumentController extends Controller
{
    public function actionIndex($mode)
    {
        return $this->render('index', ['mode' => $mode]);
    }

    public function actionDownload($id)
    {
        $model = Document::findOne($id);
        if (empty($model))
            throw new \HttpException('Страница не найдена.', 404);

        \Yii::$app->response->sendFile($model->getPath(), $model->getName());
    }
}
