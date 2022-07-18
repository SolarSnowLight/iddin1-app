<?
/**
 * @var $model \app\models\News
 */
use mrssoft\engine\helpers\Date;
use yii\helpers\Url;
?>
<div class="row news">
    <div class="col-md-3">
        <?php if (!empty($model->thumbnail)): ?>
            <a href="<?=Url::toRoute(['news/view', 'id' => $model->id]);?>" title="<?=$model->title;?>">
                <img src="<?=$model->thumbnail;?>" alt="<?=$model->title;?>" style="width: 100%; height: auto">
            </a>
        <?php endif ?>
    </div>
    <div class="col-md-9">
        <h3 style="margin-top: 0"><a href="<?=Url::toRoute(['news/view', 'id' => $model->id]);?>"><?=$model->title;?></a></h3>
        <span class="date"><?=Date::date($model->date);?></span>
        <div><?=$model->preview;?></div>
    </div>
</div>
<hr>