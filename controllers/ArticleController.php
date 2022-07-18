<?php

namespace app\controllers;

use app\models\Article;
use yii\web\Controller;

class ArticleController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        /** @var Article $model */
        $model = Article::findOne($id);
        if (empty($model))
            throw new \HttpException('Страница не найдена.', 404);

        $this->view->params['breadcrumbs'] = [
            ['label' => 'Публикации', 'url' => ['article/index']],
            ['label' => $model->title],
        ];

        return $this->render('view', ['model' => $model]);
    }
}
