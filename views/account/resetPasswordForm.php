<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = $this->title;

$form = \yii\bootstrap\ActiveForm::begin(['id' => 'request-password-reset-form']);
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 20px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 20px;']);
echo $form->field($model, 'password')
    ->textInput(['id' => 'password', 'autofocus' => true, 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('Новый пароль *', ['style' => "margin-left: 10px;"]);
echo Html::submitButton('Отправить', ['id' => 'button_update_user_info', 'class' => 'btn btn-success']);
ActiveForm::end();