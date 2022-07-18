<?
/**
 * @var $model \app\models\Mediaitem
 * @var $this yii\web\View
 */

use yii\helpers\Html;

echo Html::tag('h3', $model->title);
echo Html::tag('iframe', '', ['src' => $model->description, 'frameborder' => 0, 'width' => 697, 'height' => 450, 'allowfullscreen']);
echo Html::tag('br');
