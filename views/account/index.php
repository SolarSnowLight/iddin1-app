<?php

use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use app\models\UserInfo;
use yii\bootstrap\ActiveForm;

/* Код на JavaScript */

$js = <<<JS
    if(!$('#reset_password').is(':checked')){
        $("#password").hide();
        $("#label_password").hide();
        $("#retry_password").hide();
        $("#label_retry_password").hide();
    }

    $("#fullname").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);
    });

    $("#organization").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);
    });

    $("#phone").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);
    });

    $("#password").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);
    });

    $("#retry_password").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);
    });

    $("#reset_password").on('change', function(){
        $('#button_update_user_info').prop("disabled", false);

        if($(this).is(':checked')){
            $("#password").show();
            $("#label_password").show();
            $("#retry_password").show();
            $("#label_retry_password").show();
        }else{
            $("#password").hide();
            $("#label_password").hide();
            $("#retry_password").hide();
            $("#label_retry_password").hide();
        }
    });
JS;

/* Регистрация JavaScript в веб-приложение */
$this->registerJs($js, \yii\web\View::POS_READY);

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

echo Html::tag('h1', 'Личный кабинет', ['class' => 'title-main', 'style' => 'margin-top: 60px;']);

$form = ActiveForm::begin([
    'id' => 'update-form',
    'options' => ['class' => 'form-horizontal'],
]);


$form = \yii\bootstrap\ActiveForm::begin();
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false, 'style' => 'margin-left: 20px;']);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display', 'style' => 'margin-left: 20px;']);
echo $form->field($model, 'username')
    ->textInput(['style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input', 'readOnly' => true])
    ->label('Логин', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'fullname')
    ->textInput(['id' => 'fullname', 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('ФИО', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'email')
    ->textInput(['id' => 'email', 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input', 'readOnly' => true])
    ->label('Email', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'organization')
    ->textInput(['id' => 'organization', 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('Наименование организации (юридического лица)', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'phone')
    ->textInput(['id' => 'phone', 'placeholder' => '+7987XXXXXXX', 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('Номер телефона', ['style' => "margin-left: 10px;"]);
echo $form->field($model, 'reset_password')->checkbox(['id' => 'reset_password'])->label('Изменить пароль', ['style' => 'margin-left: 10px;']);
echo $form->field($model, 'password')
    ->passwordInput(['id' => 'password', 'style' => 'width: 50%; margin-left: 10px; ', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('Новый пароль *', ['id' => 'label_password', 'style' => "margin-left: 10px;"]);
echo $form->field($model, 'retry_password')
    ->passwordInput(['id' => 'retry_password', 'style' => 'width: 50%; margin-left: 10px;', 'class' => 'input-group input-group-lg society-form-input'])
    ->label('Повтор пароля *', ['id' => 'label_retry_password', 'style' => "margin-left: 10px;"]);
echo Html::submitButton('Редактировать', ['id' => 'button_update_user_info', 'class' => 'btn btn-success', 'disabled' => 'disabled']);
ActiveForm::end();
