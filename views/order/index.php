<?
/**
 * @var $model \app\models\OrderForm
 */

use app\models\OrderForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

echo Html::tag('h1', $this->title);

echo Html::tag('p', 'Записаться на приём вы можете:');

$items = [
    'по телефону — (8-395-52) 531697',
    'по электронной почте — iddin1@mail.ru',
    'ниже по форме'
];

$li = '';
foreach ($items as $item) {
    $li .= Html::tag('li', $item);
}

echo Html::tag('ol', $li);

$form = \yii\bootstrap\ActiveForm::begin();
    echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
    echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);
    echo $form->field($model, 'name');
    echo $form->field($model, 'day')->dropDownList(OrderForm::getDays());
    echo $form->field($model, 'time')->dropDownList(OrderForm::getTimes());
    echo $form->field($model, 'body')->textArea(['rows' => 6]);
    echo $form->field($model, 'contact');
    echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();



