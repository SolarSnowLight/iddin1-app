<?
/**
 * @var $model \app\models\ReviewForm
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<p>Здравствуйте.</p>
<p>На сайте <?=Html::a(Yii::$app->name, Url::to('/', true))?> был отправлен отзыв.</p>
<p>
    <b>Имя:</b> <?=$model->name;?><br>
    <b>Возраст:</b> <?=$model->age;?><br>
    <b>Оценка:</b> <?=$model->rating;?>
</p>
============================================================================================
<p><?=$model->body;?></p>
============================================================================================