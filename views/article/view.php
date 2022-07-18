<?
/**
 * @var $model \app\models\Article
 * @var $this yii\web\View
 */

$this->title = $model->title;
?>

<h1><?=\yii\helpers\Html::encode($model->title);?></h1>
<div class="prep-text">
    <?=$model->text;?>
    <div class="clearfix"></div>
</div>

<?

$this->beginBlock('sidebar');

echo \app\widgets\Tree::widget([
    'options' => ['class' => 'tree'],
    'query' => \app\models\Article::find()->where('public=1')
]);

$this->endBlock(); ?>