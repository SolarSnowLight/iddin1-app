<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\UploadForm;
use yii\web\UploadedFile;

class SocietyController extends Controller
{
    public function actions(){
        return [
            'index' => [
                'class' => 'app\components\SocietyFormAction',
                'className' => 'app\models\SocietyForm',
                'title' => 'Написать письмо'
            ],
            'success' => [
                'class' => 'app\components\SocietyFormSuccessAction',
                'title' => 'Написать письмо'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ]
        ];
    }
}
