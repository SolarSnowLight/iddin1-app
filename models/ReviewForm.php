<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ReviewForm extends Model
{
    public $name;
    public $rating = 10;
    public $age;
    public $body;

    public $check;

    public function rules()
    {
        return [
            [['name', 'age', 'body', 'rating'], 'required'],
            ['check', 'app\components\BotValidator'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ф.И.О',
            'age' => 'Возраст',
            'body' => 'Отзыв',
            'rating' => 'Оцените нашу работу в баллах',
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('review', ['model' => $this])
                ->setTo(Yii::$app->params['feedbackEmail'])
                ->setFrom(Yii::$app->params['noreplayEmail'])
                ->setSubject('На сайте '.Yii::$app->name .' был отправлен отзыв')
                ->send();
            return true;
        } else {
            return false;
        }
    }
}
