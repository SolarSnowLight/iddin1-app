<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
 
Здравствуйте <?= $user->username ?>,
Перейдите по ссылке ниже, чтобы сбросить свой пароль:
 
<?= $resetLink ?>