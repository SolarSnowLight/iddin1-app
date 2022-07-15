<?
/**
 * @var $model \app\models\SocietyForm
 */

use app\models\SocietyForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* Код на JavaScript */
$js = <<<JS
    $("#is_patronymic_none").on('change', function(){
        if($(this).is(':checked')){
            $("#patronymic").css("display", "none");
            $("#patronymic_label").css("display", "none");
        }else{
            $("#patronymic").css("display", "block");
            $("#patronymic_label").css("display", "block");
        }
    });

    $("#create_cabinet").on('change', function(){
        if($(this).is(':checked')){
            $("#password").css("display", "block");
            $("#retry_password").css("display", "block");
            $("#password_label").css("display", "block");
            $("#retry_password_label").css("display", "block");
        }else{
            $("#password").css("display", "none");
            $("#retry_password").css("display", "none");
            $("#password_label").css("display", "none");
            $("#retry_password_label").css("display", "none");
        }
    });
JS;

/* Регистрация JavaScript в веб-приложение */
$this->registerJs($js, \yii\web\View::POS_READY);

echo Html::tag('h1', $this->title, ['class' => 'title-main']);

echo Html::tag('div', 
    Html::tag('p', 'Поля, отмеченные *, обязательны для заполнения', ['class' => 'title', 'style' => 'margin-bottom: 10px;']) . 
    Html::tag('p', 
        Html::a('Информация о персональных данных', ['/page/bezopasnyy-internet'])  . 
        ' авторов обращений, направленных в электронном виде, хранится и обрабатывается с соблюдением требований российского законодательства о персональных данных.',
        ['class' => 'title', 'style' => 'margin-bottom: 10px;']
    )
);

$model->director = 'Директору учреждения';

$form = \yii\bootstrap\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);

    echo Html::tag('p', 'В электронной анкете в Вашем обращении укажите:', ['class' => 'title', 'style' => 'margin-bottom: 10px;']);
    echo $form->field($model, 'director')->radio(['readOnly'=>true, 'value'=> 'Директору учреждения', 'checked'=>true])->label('Директору учреждения');
    echo Html::tag('input', 'Директору учреждения', ['readOnly'=>true, 'value'=>'Директору учреждения', 'class' => 'input-group input-group-lg society-form-input']);

    echo Html::tag('br');
    echo Html::tag('br');

    echo Html::tag('p', 'В электронной анкете в Вашем обращении укажите в именительном падеже Ваши:', ['class' => 'title', 'style' => 'margin-bottom: 10px;']);
    echo $form->field($model, 'surname')->textInput(['class' => 'input-group input-group-lg society-form-input'])->label('Фамилия *');
    echo $form->field($model, 'name')->textInput(['class' => 'input-group input-group-lg society-form-input'])->label('Имя *');
    echo $form->field($model, 'patronymic')->textInput(['id'=>'patronymic', 'class' => 'input-group input-group-lg society-form-input'])->label('Отчество (при наличии) *', ['id'=>'patronymic_label']);
    echo $form->field($model, 'is_patronymic_none')->checkbox(['id'=>'is_patronymic_none']);
    
    echo $form->field($model, 'organization')->textInput(['class' => 'input-group input-group-lg society-form-input']);
    echo Html::tag('p', 'В электронной анкете в Вашем обращении укажите:', ['class' => 'title', 'style' => 'margin-bottom: 10px;']);
    echo $form->field($model, 'email')->textInput(['class' => 'input-group input-group-lg society-form-input'])->label(
        'адрес электронной почты, по которому должны быть направлены ответ, уведомление о 
        переадресации Вашего обращения (необходимо указывать адрес электронной почты, 
        который принадлежит только Вам, для обеспечения неразглашения сведений, содержащихся в 
        Вашем обращении, а также сведений, касающихся Вашей частной жизни, без Вашего согласия) *'
    );
    echo $form->field($model, 'retry_email')->textInput(['class' => 'input-group input-group-lg society-form-input'])->label(
        'Повторите адрес электронной почты для автоматической проверки правильности его заполнения'
    );
    echo $form->field($model, 'phone')->textInput(['placeholder' => '+7987XXXXXXX', 'class' => 'input-group input-group-lg society-form-input'])->label('Номер телефона');

    echo Html::tag('p', 'Напишите текст обращения', ['class' => 'title', 'style' => 'margin-bottom: 10px;']);
    echo Html::tag('p', '
    В соответствии с частью 1 статьи 7 Федерального закона от 2 мая 2006 года № 59-ФЗ «О порядке рассмотрения обращений 
    граждан Российской Федерации» гражданин в своём обращении в обязательном порядке излагает суть предложения, заявления или жалобы.
    <br>
    <br>
    Обращаем Ваше внимание, что в целях объективного и всестороннего рассмотрения Вашего обращения в установленные сроки 
    необходимо в тексте обращения указывать адрес описанного Вами места действия, факта или события.
    <br>
    <br>
    В случае, если текст Вашего обращения не позволяет определить суть предложения, заявления или жалобы, 
    ответ на обращение не дается и оно не подлежит направлению на рассмотрение в государственный орган, орган местного 
    самоуправления или должностному лицу, в соответствии с их компетенцией, о чем Вам будет сообщено в течение семи дней со дня регистрации обращения.
    <br>
    <br>
    Обращаем Ваше внимание, что при написании текста обращения в форме электронного документа в поле ввода текста 
    обращения в форме электронного документа для изложения сути предложения, заявления или жалобы отсутствует ограничение по вводимому количеству символов.');

    echo Html::tag('p', 'В поле ввода текста обращения в форме электронного документа в Вашем обращении:', ['class' => 'title', 'style' => 'margin-bottom: 10px;']);
    echo $form->field($model, 'body')->textArea(['rows' => 15, 'style' => 'color: #020c22;'])->label(
        'изложите суть предложения, заявления или жалобы *'
    );

    echo Html::tag('p', '
    В случае необходимости в подтверждение своих доводов гражданин вправе приложить к обращению необходимые 
    документы и материалы в электронной форме, воспользовавшись функцией «Прикрепить файл».
    <br>
    <br>
    Обращаем внимание, что прикрепляемые в предложенном на сайте формате документы и материалы служат 
    лишь подтверждением доводов автора обращения, изложенных в тексте обращения.
    <br>
    <br>
    Приложить необходимые документы и материалы в электронной форме можно в любой последовательности 
    двумя самостоятельными вложениями файла без архивирования (файл вложения) по одному из двух разных типов допустимых форматов:
    текстового (графического) формата: txt, doc, docx, rtf, xls, xlsx, pps, ppt, odt, ods, odp, pub, pdf, jpg, jpeg, bmp, png, tif, gif, pcx;
    аудио- (видео-) формата: mp3, wma, avi, mp4, mkv, wmv, mov, flv.
    Иные форматы не обрабатываются в информационных системах Администрации Президента Российской Федерации.
    <br>
    <br>
    При подключении оборудования пользователя к сети «Интернет» по выделенным каналам связи с использованием технологий ADSL, 3G, 4G, WiFi и иных технологий, обеспечивающих аналогичные скорости передачи данных в сети «Интернет», передача и обработка файла(ов) с суммарным размером:
    <br>
    - до 5 Мб осуществляется, как правило, без задержки во времени;<br>
    - от 5 Мб до 10 Мб может осуществляться с задержкой во времени;<br>
    - свыше 10 Мб может быть не осуществлена.
    <br>
    <br>
    Для приложения к обращению необходимых документов и материалов в электронной форме нажмите кнопку «Прикрепить файл(ы)».<br>');

    echo $form->field($model, 'files[]')->fileInput(['multiple' => true, 'class'=>"form-control"])->label('Прикрепить файл(ы)');
    /*echo $form->field($model, 'files[0]')->fileInput();
    echo $form->field($model, 'files[1]')->fileInput()->label(false);
    echo $form->field($model, 'files[2]')->fileInput()->label(false);
    echo $form->field($model, 'files[3]')->fileInput()->label(false);
    echo $form->field($model, 'files[4]')->fileInput()->label(false);*/

    echo Html::tag('p', 'Обращаем Ваше внимание, что подтверждением прикрепления файла(ов) вложения является появление строки с наименованием(ями) выбранного(ых) Вами файла(ов).<br>');

    echo $form->field($model, 'create_cabinet')->checkbox(['id' => 'create_cabinet']);

    echo $form->field($model, 'password')->textInput(['id' => 'password', 'class' => 'input-group input-group-lg society-form-input', 'style' => $model->create_cabinet ? 'display: block;' : 'display: none;'])->label('Пароль (не менее шести символов) *', ['id'=>'password_label', 'style' => $model->create_cabinet ? 'display: block;' : 'display: none;']);
    echo $form->field($model, 'retry_password')->textInput(['id' => 'retry_password', 'class' => 'input-group input-group-lg society-form-input', 'style' => $model->create_cabinet ? 'display: block;' : 'display: none;'])->label('Повторите пароль *', ['id'=>'retry_password_label', 'style' => $model->create_cabinet ? 'display: block;' : 'display: none;']);
    
    echo Html::tag('p', '
    В случае успешной отправки с гарантией доставки письма в электронной форме появляется информационное сообщение, содержащее дату и номер отправления ID.
    <br>
    <br>
    «Большое спасибо!
    Отправленное дд.мм.гггг Вами письмо в электронной форме за номером ID=ХХХХХХ будет доставлено и с момента поступления в Администрацию Президента Российской Федерации зарегистрировано в течение трех дней.».
    ');
    
    echo Html::submitButton('Направить письмо', ['class' => 'btn btn-success']);
ActiveForm::end();
