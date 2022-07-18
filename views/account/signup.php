<?php 

use app\models\SignupForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* Код на JavaScript */
$js = <<<JS
    $("#is_patronymic_none").on('change', function(){
        if($(this).is(':checked')){
            $("#patronymic").css("display", "none");
            $("#patronymic_label").css("display", "none");
        }else{
            $("#patronymic").css("display", "block");
            $("#patronymic_label").css("display", "block");
        }
    });

    $("#create_cabinet").on('change', function(){
        if($(this).is(':checked')){
            $("#password").css("display", "block");
            $("#retry_password").css("display", "block");
            $("#password_label").css("display", "block");
            $("#retry_password_label").css("display", "block");
        }else{
            $("#password").css("display", "none");
            $("#retry_password").css("display", "none");
            $("#password_label").css("display", "none");
            $("#retry_password_label").css("display", "none");
        }
    });
JS;

/* Регистрация JavaScript в веб-приложение */
$this->registerJs($js, \yii\web\View::POS_READY);

echo Html::tag('h1', 'Регистрация пользователя', ['class' => 'title-main']);

$form = \yii\bootstrap\ActiveForm::begin();
    //echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    //echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);

    echo $form->field($model, 'username')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Логин *');
    echo $form->field($model, 'surname')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Фамилия *');
    echo $form->field($model, 'name')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Имя *');
    echo $form->field($model, 'patronymic')->textInput(['id'=>'patronymic', 'style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Отчество (при наличии) *', ['id'=>'patronymic_label']);
    echo $form->field($model, 'is_patronymic_none')->checkbox(['id'=>'is_patronymic_none']);
    echo $form->field($model, 'organization')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input']);
    echo $form->field($model, 'phone')->textInput(['placeholder' => '+7987XXXXXXX', 'style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Номер телефона');
    echo $form->field($model, 'email')->textInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Email *');
    echo $form->field($model, 'password')->passwordInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Пароль *');
    echo $form->field($model, 'retry_password')->passwordInput(['style' => 'width: 50%', 'class' => 'input-group input-group-lg society-form-input'])->label('Повторите пароль *');

    echo Html::submitButton('Регистрация', ['class' => 'btn btn-success']);

    echo Html::tag('div', 
        'Уже есть аккаунт?' . Html::a(' Авторизоваться', ['/account/signin']),
        ['class' => 'title', 'style' => 'margin-bottom: 10px; margin-top: 10px;']
    );
ActiveForm::end();