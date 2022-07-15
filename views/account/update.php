<?php

use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

echo Nav::widget([
    'options' => ['class' => 'navbar-inverse navbar-right'],
    'items' => [
        [
            'label' => \Yii::t('account', 'Logout') . ' (' . \Yii::$app->user->identity->username . ')',
            'url' => ['account/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
        [
            'label' => \Yii::t('account', 'Список заявок'),
            'url' => ['account/list'],
            'linkOptions' => ['data-method' => 'post']
        ]
    ],
]);

echo Html::tag('h1', 'Редактирование заявки', ['class' => 'title-main']);

$form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]);


$form = \yii\bootstrap\ActiveForm::begin();
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 10px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 10px;']);
echo $form->field($model, 'name')
    ->textInput(['class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('ФИО', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'email')
    ->textInput(['class' => 'input-group input-group-lg society-form-input', 'readOnly'=>true, 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Email', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'address')
    ->textArea(['class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Наименование организации (юридического лица)', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'phone')
    ->textInput(['class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Номер телефона', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'body')
    ->textArea(['rows' => 20, 'style' => 'color: #020c22; margin-left: 10px; width: 95%;'])->label('Текст обращения', ['style' => "margin-left: 10px;"]);
echo Html::submitButton('Редактировать', ['class' => 'btn btn-success']);
ActiveForm::end();
