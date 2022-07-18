<?
/**
 * @var $model \app\models\PollForm
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<p>Здравствуйте.</p>
<p>На сайте <?=Html::a(Yii::$app->name, Url::to('/', true))?> был отправлен опросный лист.</p>
<p>
    <b>Имя:</b> <?=$model->name;?><br>
    <b>Контакты:</b> <?=$model->contact;?><br>
    <b>Довольны ли Вы результатом обращения к нашему специалисту:</b> <?=$model->result;?>
</p>
============================================================================================
<p>
<?php for ($i = 1; $i <= 5; $i++): ?>
    <b><?=$model->getAttributeLabel('q' . $i) . ':</b> ' . implode(', ', (array)$model->{'q' . $i});?> <br>
<?php endfor ?>
    <b>Опишите конфликтную ситуацию:</b> <?=$model->conflict;?><br>
</p>
============================================================================================