<?
namespace app\components;

use yii\validators\Validator;

class BotValidator extends Validator
{
    protected function validateValue($value)
    {
        if (!empty($value)) \Yii::$app->end();
    }
}