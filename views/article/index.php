<?
/**
 * @var $this \yii\web\View
 */

$this->title = 'Публикации';

echo \yii\helpers\Html::tag('h1', $this->title);

echo \app\widgets\Tree::widget([
    'options' => ['class' => 'tree'],
    'query' => \app\models\Article::find()->where('public=1')
]);
