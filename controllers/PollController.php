<?php

namespace app\controllers;

use yii\web\Controller;

class PollController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'app\components\FormAction',
                'className' => 'app\models\PollForm',
                'title' => 'Опросный лист получателей социальных услуг'
            ],
            'success' => [
                'class' => 'app\components\SuccessAction',
                'title' => 'Опросный лист получателей социальных услуг'
            ]
        ];
    }
}
