<?php

namespace app\controllers;

use yii\web\Controller;

class ReviewController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => '\app\components\FormAction',
                'className' => '\app\models\ReviewForm',
                'title' => 'Отправить отзыв, жалобу,  благодарность'
            ],
            'success' => [
                'class' => '\app\components\SuccessAction',
                'title' => 'Отправка отзыва'
            ]
        ];
    }
}
