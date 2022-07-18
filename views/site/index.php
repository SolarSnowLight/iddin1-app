<?
/**
 * @var $model \app\models\SiteForm
 */

use app\models\SiteForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

echo Html::tag('h1', $this->title);

$form = \yii\bootstrap\ActiveForm::begin();
echo Html::errorSummary($model, ['class' => 'alert alert-danger', 'header' => false]);
echo Html::activeTextInput($model, 'check', ['class' => 'no-display']);
echo $form->field($model, 'info')->dropDownList(SiteForm::getInfo());
echo $form->field($model, 'source')->radioList(SiteForm::getSource());
echo $form->field($model, 'speed')->radioList(SiteForm::getSpeed());
echo $form->field($model, 'part')->checkboxList(SiteForm::getPart());
echo $form->field($model, 'upgrade')->checkboxList(SiteForm::getUpgrade());
echo $form->field($model, 'rating')->dropDownList(SiteForm::getRating());
echo $form->field($model, 'comment')->textarea(['rows' => 6]);
echo $form->field($model, 'name');
echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();



