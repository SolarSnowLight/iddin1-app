<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\components\FormAction',
                'className' => 'app\models\SiteForm',
                'title' => 'Оцените сайт'
            ],
            'success' => [
                'class' => 'app\components\SuccessAction',
                'title' => 'Оцените сайт'
            ]
        ];
    }
}
