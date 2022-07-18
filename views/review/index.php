<?
/**
 * @var $model \app\models\ReviewForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

echo Html::tag('h1', $this->title);

echo Html::tag('p', 'Наш коллектив работает над улучшением качества оказания услуг, поэтому нам важно знать Ваше мнение о работе нашего учреждения. Пожалуйста, оставьте свой отзыв или напишите жалобу.');

$form = \yii\bootstrap\ActiveForm::begin();
    echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);
    echo $form->field($model, 'name');
    echo $form->field($model, 'age');
    echo $form->field($model, 'body')->textArea(['rows' => 6]);
    echo $form->field($model, 'rating')->dropDownList([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10]);
    echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();



