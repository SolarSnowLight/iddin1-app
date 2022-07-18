<?
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use app\widgets\Archive;

$this->title = Yii::t('site', 'News');

echo \yii\helpers\Html::tag('h1', $this->title);

?>
<div class="row">
    <div class="col-xs-9">
        <? echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'item',
            'layout' => '{pager}{items}{pager}'
        ]); ?>
    </div>
    <div class="col-xs-3">
        <? echo Archive::widget();?>
    </div>
</div>