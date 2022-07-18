<?
/**
 * @var $this \yii\web\View
 * @var string $content
 */
use app\widgets\Menu;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginContent('@app/views/layouts/base.php');?>
    <div class="row">
        <div class="col-xs-3 sidebar">
            <?php echo Menu::widget(['className' => '\app\models\Menu', 'options' => ['class' => 'menu']]); ?>
        </div>
        <div class="col-xs-9 block-main">
            <?
            echo Breadcrumbs::widget(['homeLink' => false, 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]);
            echo $content;
            ?>
        </div>
    </div>
<?php $this->endContent();?>