<?
/**
 * @var $model \app\models\FeedbackForm
 */

use app\models\PollForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

echo Html::tag('h1', $this->title);

echo Html::tag('p1', 'Коллектив нашего учреждения стремиться сделать наш дом комфортным, светлым и уютным для всех. Нам важно Ваше мнение о работе нашего учреждения.
 Мы будем благодарны, если Вы примите участие в опросе.');

echo Html::tag('hr');
$form = ActiveForm::begin();
    echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);
    echo $form->field($model, 'q1')->checkboxList(PollForm::getQ(1));
    echo $form->field($model, 'q2')->radioList(PollForm::getQ(2));
    echo $form->field($model, 'q3')->checkboxList(PollForm::getQ(3));
    echo $form->field($model, 'q4')->radioList(PollForm::getQ(4));
    echo $form->field($model, 'q5')->radioList(PollForm::getQ(5));
    echo $form->field($model, 'conflict')->textarea(['rows' => 4]);
    echo $form->field($model, 'result');
    echo $form->field($model, 'name');
    echo $form->field($model, 'contact');
    echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();



