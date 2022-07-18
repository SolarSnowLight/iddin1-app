<?php

namespace app\controllers;

use app\models\News;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class NewsController extends Controller
{
    public function actions()
    {
        return [
            'archive' => '\app\components\ArchiveAction'
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => News::active()->orderBy(['date' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 10,
                    'pageSizeParam' => false,
                ]
            ])
        ]);
    }

    public function actionView($id)
    {
        /** @var News $model */
        $model = News::active()->where(['id' => $id])->one();
        if (empty($model))
            throw new \HttpException(Yii::t('yii', 'Page not found.'), 404);

        $this->view->params['breadcrumbs'] = [
            [ 'label' => Yii::t('site', 'News'), 'url' => ['news/index'] ],
            [ 'label' => $model->title, 'url' => ['news/index', 'id' => $model->id] ]
        ];

        return $this->render('view', ['model' => $model]);
    }
}
