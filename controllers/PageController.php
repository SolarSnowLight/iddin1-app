<?php

namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;

class PageController extends Controller
{
    public function actionMain()
    {
        $this->layout = 'home';
        return $this->actionView('main');
    }

    public function actionView($url)
    {
        /** @var Page $model */
        $model = Page::findByUrl($url);
        if (empty($model))
            throw new \HttpException(Yii::t('yii', 'Page not found.'), 404);

        if (!empty($model->parent_id)) {
            $url = $model->parent->url == 'home' ? '/' : ['page/view', 'url' => $model->parent->url];
            $this->view->params['breadcrumbs'] = [
                ['label' => $model->parent->title, 'url' => $url],
                ['label' => $model->title],
            ];
        }

        return $this->render('view', ['model' => $model]);
    }
}