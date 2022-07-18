<?
/**
 * @var $model \app\models\SiteForm
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<p>Здравствуйте.</p>
<p>На сайте <?=Html::a(Yii::$app->name, Url::to('/', true))?> была сделана оценка сайта.</p>
<p>
    <?php foreach ($model->attributes() as $attribute): ?>
        <?php if(!empty($model->{$attribute})): ?>
            <b><?=$model->getAttributeLabel($attribute)?>:</b> <?=$attribute === 'part' || $attribute === 'upgrade' ? implode(', ', $model->{$attribute})  :  $model->{$attribute};?><br>
    <?php endif ?>
    <?php endforeach ?>
</p>