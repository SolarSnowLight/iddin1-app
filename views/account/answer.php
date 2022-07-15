<?php

use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;

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
    'options' => ['class' => 'navbar-inverse navbar-right'],
    'items' => [
        [
            'label' => \Yii::t('account', 'Logout') . ' (' . \Yii::$app->user->identity->username . ')',
            'url' => ['account/logout'],
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
        ] : ''
    ],
]);

echo Html::tag('h1', 'Ответ на обращение', ['class' => 'title-main']);
echo Html::tag('br');
echo Html::tag('br');

$form = \yii\bootstrap\ActiveForm::begin();
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 10px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 10px;']);
echo $form->field($model, 'id_society')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Номер обращение', ['style' => "margin-left: 10px;"]);
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
echo Html::submitButton('Ответить', ['class' => 'btn btn-success']);
ActiveForm::end();
