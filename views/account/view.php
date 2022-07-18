<?php

use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

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
        (!$check) ? [
            'label' => \Yii::t('account', 'Ответы на обрещения'),
            'url' => ['account/answer-list'],
            'linkOptions' => ['data-method' => 'post']
        ] : '',
        [
            'label' => \Yii::t('account', 'Выход') . ' (' . \Yii::$app->user->identity->username . ')',
            'url' => ['account/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
    ],
]);

echo Html::tag('h1', 'Просмотр обращения', ['class' => 'title-main', 'style' => 'margin-top: 60px;']);

$form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]);


$form = \yii\bootstrap\ActiveForm::begin();
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 10px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 10px;']);
echo $form->field($model, 'id')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Номер обращения', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'name')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('ФИО', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'email')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Email', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'address')
    ->textArea(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Наименование организации (юридического лица)', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'phone')
    ->textInput(['readonly' => true, 'class' => 'input-group input-group-lg society-form-input', 'style' => 'margin-left: 10px; width: 95%;'])
    ->label('Номер телефона', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'body')
    ->textArea(['readonly' => true, 'rows' => 20, 'style' => 'color: #020c22; margin-left: 10px; width: 95%;'])->label('Текст обращения', ['style' => "margin-left: 10px;"]);
//echo ($check)? Html::submitButton('Ответить', ['class' => 'btn btn-success']) : '';
ActiveForm::end();

echo ($check) ? Html::tag(
    'p',
    Html::a('Ответить', ['/account/answer?id=' . $model->id]),
    ['class' => 'title', 'style' => 'margin-bottom: 10px;']
) : '';
