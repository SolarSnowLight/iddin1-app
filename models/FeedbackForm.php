<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FeedbackForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;

    public $check;

    public function rules()
    {
        return [
            [['name', 'email', 'body'], 'required'],
            ['email', 'email'],
            ['check', \app\components\BotValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'E-mail',
            'body' => 'Сообщение',
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('feedback', ['model' => $this])
                ->setTo(Yii::$app->params['feedbackEmail'])
                ->setFrom(Yii::$app->params['noreplayEmail'])
                ->setSubject('Сообщение с сайта '.Yii::$app->name)
                ->send();
            return true;
        } else {
            return false;
        }
    }
}
