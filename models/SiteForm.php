<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SiteForm extends Model
{
    public $name;
    public $info;
    public $source;
    public $speed;
    public $part;
    public $upgrade;
    public $rating;
    public $comment;

    public $check;

    public function rules()
    {
        return [
            [['name', 'info', 'source', 'speed', 'part', 'upgrade', 'rating'], 'required'],
            [['comment'], 'string'],
            [['part', 'upgrade'], 'safe'],
            [['info'], 'in', 'range' => self::getInfo()],
            [['source'], 'in', 'range' => self::getSource()],
            [['speed'], 'in', 'range' => self::getSpeed()],
            //[['part'], 'in', 'range' => self::getPart()],
            //[['upgrade'], 'in', 'range' => self::getUpgrade()],
            [['rating'], 'in', 'range' => self::getRating()],
            ['check', 'app\components\BotValidator'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ф.И.О',
            'info' => 'Насколько полная информация об учреждении на сайте?',
            'source' => 'Каким способом Вы нашли информацию?',
            'speed' => 'Вы быстро нашли интересующую Вас информацию?',
            'part' => 'Какой раздел сайта Вам больше всего понравился?',
            'upgrade' => 'Что бы Вам хотелось усовершенствовать в работе сайта?',
            'rating' => 'Оцените в целом наш сайт',
            'comment' => 'Здесь Вы можете оставить пожелания по сайту',
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('site', ['model' => $this])
                ->setTo(Yii::$app->params['feedbackEmail'])
                ->setFrom(Yii::$app->params['noreplayEmail'])
                ->setSubject('На сайте '.Yii::$app->name .' была отправлена оценка сайта')
                ->send();
            return true;
        } else {
            return false;
        }
    }

    public static function getSource()
    {
        return [
            'Навигацией по вкладкам' => 'Навигацией по вкладкам',
            'Воспользовался поиском' => 'Воспользовался поиском',
        ];
    }

    public static function getSpeed()
    {
        return [
            'Быстро' => 'Быстро',
            'Пришлось искать' => 'Пришлось искать',
            'Долго' => 'Долго',
            'Не нашел информации' => 'Не нашел информации',
        ];
    }

    public static function getInfo()
    {
        return [
            'Полная' => 'Полная',
            'Частичная' => 'Частичная',
            'Неполная' => 'Неполная',
        ];
    }

    public static function getPart()
    {
        return [
            'Навигация' => 'Навигация',
            'Полнота информации' => 'Полнота информации',
            'Размещение информации' => 'Размещение информации',
            'Возможность поиска' => 'Возможность поиска',
            'Возможность обратиться' => 'Возможность обратиться',
        ];
    }

    public static function getUpgrade()
    {
        return [
            'Оформление сайта' => 'Оформление сайта',
            'Разделы сайта' => 'Разделы сайта',
            'Ваши предложения ' => 'Ваши предложения ',
        ];
    }

    public static function getRating()
    {
        return [
            '5' => '5',
            '4' => '4',
            '3' => '3',
            '2' => '2',
            '1' => '1',
        ];
    }

}