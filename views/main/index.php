<?php
/* @var $this yii\web\View */

use app\models\Module;
use app\models\News;
use app\models\Page;
use yii\helpers\Html;
use yii\widgets\ListView;
use mrssoft\engine\helpers\Date;

$this->title = Yii::$app->name;

/** @var Page $page */
$page = Page::findByUrl('home');
if ($page) {
    echo $page->text;
}

echo Module::getContent('social-links');

echo Html::tag('h3', 'Новости', ['class' => 'caption']);
echo ListView::widget([
    'dataProvider' => News::last(3),
    'itemView' => '/news/item_last',
    'layout' => '{items}',
    'options' => ['class' => 'row']
]);

$data = file_get_contents('https://open.irkobl.ru/rss.php/');

function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

console_log($data);
//$xml = new SimpleXMLElement($data);
$index = 0;
?>
<h3 class="caption">Новости региона <a href="https://open.irkobl.ru/news-region/" target="_blank" style="font-size: 14px;">Смотреть все</a></h3>
<div id="w0" class="row">
    <? foreach ($xml->channel->item as $xmlItem) : ?>
        <?
        $date = new DateTime($xmlItem->pubDate);
        if ($index == 3) break;
        $index++;
        ?>
        <div>
            <div class="news-last col-xs-4">
                <div class="date"><?= Date::date($date->format('Y-m-d')) ?></div>
                <a href="<?= $xmlItem->link ?>" target="_blank"><img class="image" src="<?= $xmlItem->enclosure['url'] ?>" alt="<?= $xmlItem->title ?>"></a>
                <a class="title" href="<?= $xmlItem->link ?>" target="_blank"><?= $xmlItem->title ?></a>
            </div>
        </div>
    <? endforeach; ?>
</div>