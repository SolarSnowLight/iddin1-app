<?
/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use app\widgets\ColumnList;

echo \yii\helpers\Html::tag('h1', $this->title);

echo ColumnList::widget([
    'columnCount' => 4,
    'itemOptions' => ['class' => 'col-xs-3 gallery'],
    'itemView' => 'item',
	'layout' => '{pager}{items}',
    'dataProvider' => $dataProvider
]);