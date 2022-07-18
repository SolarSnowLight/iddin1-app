<?
/**
 * @var $model \app\models\FeedbackForm
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Обратная связь';

echo Html::tag('h1', $this->title);

$form = \yii\bootstrap\ActiveForm::begin();
    echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);
    echo $form->field($model, 'name');
    echo $form->field($model, 'email');
    echo $form->field($model, 'body')->textArea(['rows' => 6]);
    echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();



