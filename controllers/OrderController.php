<?php

namespace app\controllers;

use yii\web\Controller;

class OrderController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\components\FormAction',
                'className' => 'app\models\OrderForm',
                'title' => 'Запись на приём'
            ],
            'success' => [
                'class' => 'app\components\SuccessAction',
                'title' => 'Запись на приём'
            ]
        ];
    }
}
