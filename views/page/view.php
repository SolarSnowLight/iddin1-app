<?
/**
 * @var $model \app\models\Page
 * @var $this yii\web\View
 */

use yii\helpers\Html;

$this->title = $model->title;

if (empty($this->params['hideTitle'])) echo Html::tag('h1', Html::encode($model->title));
?>
<div class="prep-text">
    <?=$model->text;?>
    <div class="clearfix"></div>
</div>