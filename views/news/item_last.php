<?
/**
 * @var $model \app\models\News
 */
use mrssoft\engine\helpers\Date;
use yii\helpers\Html;

$model->title = Html::encode($model->title);

$content = '';
$content .= Html::tag('div', Date::date($model->date), ['class' => 'date']);
if (!empty($model->thumbnail)) {
    $content .= Html::a(Html::img($model->thumbnail, ['alt' => $model->title, 'class' => 'image']), ['news/view', 'id' => $model->id]);
}
$content .= Html::a($model->title, ['news/view', 'id' => $model->id], ['class' => 'title']);

echo Html::tag('div', $content, ['class' => 'news-last col-xs-4']);
