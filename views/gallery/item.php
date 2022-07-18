<?
/**
 * @var $model \app\models\Media
 */
use yii\helpers\Html;

echo Html::a('', ['gallery/view', 'id' => $model->id],
    ['class' => 'thumb', 'style' => 'background-image:url('.$model->main->getThumbnail().')']
);
echo Html::a($model->title, ['gallery/view', 'id' => $model->id], ['class' => 'name']);







