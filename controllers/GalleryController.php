<?php

namespace app\controllers;

use app\models\Media;
use app\models\Mediaitem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;

class GalleryController extends Controller
{
    public function actionIndex($type = null)
    {
        $query = Media::find()->select([Media::tableName().'.id', Media::tableName().'.title'])
                              ->where([Media::tableName().'.public' => 1])
                              ->joinWith('main');

        if ($type) {
            $this->view->title = Yii::t('site', ucfirst($type));

            $this->view->params['breadcrumbs'] = [
                ['label' => Yii::t('site', 'Gallery'), 'url' => ['gallery/index']],
                ['label' => $this->view->title, 'url' => ['gallery/index', 'type' => $type]],
            ];

            $query->andWhere(['type' => $type]);
        } else {
            $this->view->title = Yii::t('site', 'Gallery');
        }

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 16,
                ],
                'sort' => [
                    'defaultOrder' => ['date' => SORT_DESC]
                ]
            ]),
        ]);
    }

    public function actionView($id)
    {
        /** @var Media $model */
        $model = Media::active()->where(['id' => $id])->one();
        if (empty($model))
            throw new \HttpException(Yii::t('yii', 'Page not found.'), 404);

        $this->view->params['breadcrumbs'] = [
            [ 'label' => Yii::t('site', 'Gallery'), 'url' => ['gallery/index'] ],
            [ 'label' => Yii::t('site', ucfirst($model->type)), 'url' => ['gallery/index', 'type' => $model->type]],
            [ 'label' => $model->title, 'url' => ['gallery/view', 'id' => $id] ]
        ];

        $this->view->title = $model->title;

        return $this->render('view', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Mediaitem::active()->where(['media_id' => $id]),
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => ['position' => SORT_ASC]
                ]
            ]),
            'model' => $model,
        ]);
    }
}
