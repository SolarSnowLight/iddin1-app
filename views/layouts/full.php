<?
/**
 * Шаблон главной страницы
 */
?>
<?php $this->beginContent('@app/views/layouts/base.php');?>
    <div class="row" id="main">
        <div class="col-xs-12 block-main">
            <?=$content;?>
        </div>
    </div>
<?php $this->endContent();?>