<?php

namespace app\controllers;

use yii\web\Controller;

class FeedbackController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\components\FormAction',
                'className' => 'app\models\FeedbackForm',
                'title' => 'Обратная связь'
            ],
            'success' => [
                'class' => 'app\components\SuccessAction',
                'title' => 'Обратная связь'
            ]
        ];
    }
}
