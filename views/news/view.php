<?
/**
 * @var $model \app\models\Page
 * @var $this yii\web\View
 */

$this->title = $model->title;
?>

<h1><?=\yii\helpers\Html::encode($model->title);?></h1>
<div class="prep-text">
    <?=$model->text;?>
    <div class="clearfix"></div>
</div>