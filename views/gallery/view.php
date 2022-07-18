<?
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $this yii\web\View
 * @var $model \app\models\Media
 */

use app\widgets\ColumnList;
use yii\helpers\Html;
use yii\widgets\ListView;

echo Html::tag('h1', $model->title);
echo Html::tag('div', $model->description, ['class' => 'prep-text']);

if ($model->type == 'video') {
    echo ListView::widget([
        'itemView' => 'video',
        'dataProvider' => $dataProvider,
        'layout' => '{items}'
    ]);
} else {
    echo ColumnList::widget([
        'columnCount' => 4,
        'itemOptions' => ['class' => 'col-xs-3 gallery'],
        'itemView' => 'photo',
        'dataProvider' => $dataProvider
    ]);

    mrssoft\mrs2000box\Asset::register($this);
    $this->registerJs('$(document).ready(function () { $(".mrsbox").mrs2000box({advanced:true}); });', \yii\web\View::POS_END);
}