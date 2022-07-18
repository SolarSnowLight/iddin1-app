<?php 

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

echo Html::tag('h1', 'Авторизация пользователя', ['class' => 'title-main']);

$form = \yii\bootstrap\ActiveForm::begin();
    //echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    //echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);

    echo $form->field($model, 'username')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Логин / Email');
    echo $form->field($model, 'password')->passwordInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Пароль');

    echo Html::submitButton('Войти', ['class' => 'btn btn-success']);

    echo Html::tag('div', 
        'Нет аккаунта?' . Html::a(' Зарегистрироваться', ['/account/signup']),
        ['class' => 'title', 'style' => 'margin-bottom: 10px; margin-top: 10px;']
    );

    echo Html::tag('div', 
    'Забыл пароль?' . Html::a(' Восстановить пароль', ['/account/request-password-reset']),
    ['class' => 'title', 'style' => 'margin-bottom: 10px; margin-top: 10px;']
);
ActiveForm::end();