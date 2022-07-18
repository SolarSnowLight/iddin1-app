<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php $this->beginBody() ?>
    <div class="content">
        <div class="container">
            <header id="header" class="row">
                <a href="/" class="logo"><img src="/img/front/logo1.png" width="159" height="138"></a>
                <div class="name">
                    <div class="min">
                        МИНИСТЕРСТВО СОЦИАЛЬНОГО РАЗВИТИЯ,<br>ОПЕКИ И&nbsp;ПОПЕЧИТЕЛЬСТВА ИРКУТСКОЙ ОБЛАСТИ
                    </div>
                    <span class="orange">Иркутский детский дом-интернат №1</span>
                    <span class="green">для умственно отсталых детей</span>
                </div>
                <div class="address">
                    664059, Иркутская область, г. Иркутск, 6-й Поселок ГЭС, дом 3А<br>
                    Тел.: (83952) 53-16-97
                </div>
                <a href="#" class="changeFontSize">Версия для слабовидящих</a>
                <!-- yandex search -->
                <div class="ya-site-form ya-site-form_inited_no" onclick="return {'action':'http://iddin1.ru/search','arrow':false,'bg':'transparent','fontsize':12,'fg':'#000000','language':'ru','logo':'rb','publicname':'Поиск по ИДДИ№1','suggest':true,'target':'_self','tld':'ru','type':2,'usebigdictionary':true,'searchid':2236412,'input_fg':'#000000','input_bg':'#ffffff','input_fontStyle':'normal','input_fontWeight':'normal','input_placeholder':'Поиск по сайту','input_placeholderColor':'#000000','input_borderColor':'#7f9db9'}">
                    <form action="http://yandex.ru/search/site/" method="get" target="_self">
                        <input type="hidden" name="searchid" value="2236412"/>
                        <input type="hidden" name="l10n" value="ru"/>
                        <input type="hidden" name="reqenc" value="utf-8"/>
                        <input type="search" name="text" value=""/>
                        <input type="submit" value="Найти"/>
                    </form>
                </div>
                <style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>
                <!-- yandex search -->
            </header>
            <?=$content;?>
            <a href="#" class="button-up"></a>
            <span id="sputnik-informer"></span>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="pull-left">
                        <ul class="footer-menu">
                            <li><a href="mailto:iddin1@mail.ru" title="Напишите нам">iddin1@mail.ru</a></li>
                            <li><a href="/" title="Главная"><i class="glyphicon glyphicon-home"></i></a></li>
                            <li><a href="<?=Url::to(['page/view', 'url' => 'contact']);?>" title="Контакты"><i class="glyphicon glyphicon-envelope"></i></a></li>
                        </ul>
                    </div>
                    <div class="pull-right">
                        <!-- Yandex.Metrika informer -->
                        <a href="https://metrika.yandex.ru/stat/?id=31417153&amp;from=informer"
                           target="_blank" rel="nofollow"><img src="https://mc.yandex.ru/informer/31417153/3_0_FFA020FF_FF8000FF_0_pageviews"
                                                               style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:31417153,lang:'ru'});return false}catch(e){}" /></a>
                        <!-- /Yandex.Metrika informer -->
                    </div>
                </div>
            </div>
        </div>
    </footer>

<?php $this->endBody() ?>
<?php if (!YII_DEBUG && YII_ENV != 'dev'): ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter31417153 = new Ya.Metrika({
                        id:31417153,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/31417153" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
<?php endif ?>
<!--Спутник-->

<script type="text/javascript">
    (function(d, t, p) {
        var j = d.createElement(t); j.async = true; j.type = "text/javascript";
        j.src = ("https:" == p ? "https:" : "http:") + "//stat.sputnik.ru/cnt.js";
        var s = d.getElementsByTagName(t)[0]; s.parentNode.insertBefore(j, s);
    })(document, "script", document.location.protocol);
</script>
<!--Спутник-->
</body>
</html>
<?php $this->endPage() ?>