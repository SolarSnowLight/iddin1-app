<?php

use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\models\AnswerForm;

/* Код на JavaScript */
/*$js = <<<JS
    $("#file").on('change', function(){
        console.log($('#file'));
    });
JS;*/

/* Регистрация JavaScript в веб-приложение */
//$this->registerJs($js, \yii\web\View::POS_READY);

function checkAccess()
{
    $user = [];
    $userAssigned = \Yii::$app->authManager->getAssignments(\Yii::$app->getUser()->identity->id);
    foreach ($userAssigned as $userAssign) {
        $user[] = $userAssign->roleName;
    }

    return (in_array('admin', $user) || in_array('moderator', $user));
}

$check = checkAccess();

echo Nav::widget([
    'options' => ['class' => 'navbar-inverse navbar-right nav-pills'],
    'items' => [
        [
            'label' => \Yii::t('account', 'Личный кабинет'),
            'url' => ['account/index'],
            'linkOptions' => ['data-method' => 'post']
        ],
        (!$check) ? [
            'label' => \Yii::t('account', 'Список обращений'),
            'url' => ['account/list'],
            'linkOptions' => ['data-method' => 'post']
        ] : '',
        $check ? [
            'label' => \Yii::t('account', 'Список обращений'),
            'url' => ['account/admin'],
            'linkOptions' => ['data-method' => 'post']
        ] : '',
        [
            'label' => \Yii::t('account', 'Выход') . ' (' . \Yii::$app->user->identity->username . ')',
            'url' => ['account/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
    ],
]);

echo Html::tag('h1', 'Ответ на обращение', ['class' => 'title-main', 'style' => 'margin-top: 60px;']);
echo Html::tag('br');
echo Html::tag('br');

$form = \yii\bootstrap\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 10px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 10px;']);
echo $form->field($model, 'id_society')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Номер обращения', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'to_email')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Email-адрес получателя', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'fullname')
    ->textInput(['class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('ФИО Отправителя *', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'body')
    ->textArea(['rows' => 20, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Напишите текст ответа *', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'phone')
    ->textInput(['placeholder' => '+7987XXXXXXX', 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])->label('Напишите номер телефона для связи', ['style' => "margin-left: 10px;"]);
    
    echo $form->field($model, 'files[]')->fileInput(['multiple' => true, 'class'=>"form-control"])->label('Прикрепить файл(ы)');
    /*echo $form->field($model, 'files[0]')->fileInput();
    echo $form->field($model, 'files[1]')->fileInput()->label(false);
    echo $form->field($model, 'files[2]')->fileInput()->label(false);
    echo $form->field($model, 'files[3]')->fileInput()->label(false);
    echo $form->field($model, 'files[4]')->fileInput()->label(false);*/

echo Html::submitButton('Ответить', ['class' => 'btn btn-success']);
ActiveForm::end();
