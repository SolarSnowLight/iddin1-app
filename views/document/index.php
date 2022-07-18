<?
/**
 * @var $this \yii\web\View
 * @var string $mode
 */

use app\models\Document;

$this->title = Document::getModeName($mode);

echo \yii\helpers\Html::tag('h1', $this->title);

echo \app\widgets\Tree::widget([
    'options' => ['class' => 'tree'],
    'query' => Document::find()->where('public=1 AND mode=:mode', [':mode' => $mode]),
    'actionItem' => 'download'
]);
