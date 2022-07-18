<?php
 
use yii\helpers\Html;
 
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
 
<div class="password-reset">
    <p>Здравствуйте <?= Html::encode($user->username) ?>,</p>
    <p>Перейдите по ссылке ниже, чтобы сбросить свой пароль:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>