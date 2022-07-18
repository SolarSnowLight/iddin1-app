<?php

namespace app\models;

use Yii;
use yii\base\Model;

class OrderForm extends Model
{
    public $name;
    public $time;
    public $day;
    public $body;
    public $contact;

    public $check;

    public function rules()
    {
        return [
            [['name', 'day', 'time', 'contact'], 'required'],
            [['body'], 'string'],
            [['day'], 'in', 'range' => self::getDays()],
            [['time'], 'in', 'range' => self::getTimes()],
            ['check', 'app\components\BotValidator'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ф.И.О',
            'day' => 'День недели',
            'time' => 'Время',
            'body' => 'Сообщение',
            'contact' => 'Контакты для связи',
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('order', ['model' => $this])
                ->setTo(Yii::$app->params['feedbackEmail'])
                ->setFrom(Yii::$app->params['noreplayEmail'])
                ->setSubject('На сайте '.Yii::$app->name .' была сделана запись на приём')
                ->send();
            return true;
        } else {
            return false;
        }
    }

    public static function getDays()
    {
        return [
            'Понедельник' => 'Понедельник',
            'Среда' => 'Среда',
        ];
    }

    public static function getTimes()
    {
        return [
            '15:00' => '15:00',
            '15:30' => '15:30',
            '16:00' => '16:00',
            '16:30' => '16:30',
            '17:00' => '17:00',
            '17:30' => '17:30',
        ];
    }
}