<?
/**
 * @var $model \app\models\Mediaitem
 * @var $this yii\web\View
 */

use yii\helpers\Html;

echo Html::a(Html::img($model->getThumb()), $model->getImage(), ['class' => 'mrsbox']);


