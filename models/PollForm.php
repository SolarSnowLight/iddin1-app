<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\BotValidator;

class PollForm extends Model
{
    public $name;
    public $contact;
    public $q1;
    public $q2;
    public $q3;
    public $q4;
    public $q5;
    public $conflict;
    public $result;

    public $check;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'conflict', 'result'], 'trim'],
            [['name'], 'required'],
            [['q1', 'q2', 'q3', 'q4', 'q5'], 'safe'],
            ['check', BotValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'contact' => 'Контактные данные',
            'conflict' => 'Опишите конфликтную ситуацию',
            'result' => 'Довольны ли Вы результатом обращения к нашему специалисту?',
            'q1' => 'Что на ваш взгляд является положительным в работе нашего учреждения?',
            'q2' => 'Насколько наш сайт помогает Вам получить информацию об учреждении?',
            'q3' => 'К каким сотрудникам учреждения Вы обращались?',
            'q4' => 'Достаточно времени Вам уделил наш  специалист?',
            'q5' => 'Возникал ли конфликт со специалистом нашего учреждения',
        ];
    }

    public static function getQ($index)
    {
        static $mas = [
            1 => [
                'Предоставление социальных услуг',
                'Профессионализм персонала',
                'Материально-техническая база',
            ],
            2 => [
                'В полном объеме',
                'Частично',
                'Информация не в полном объеме',
            ],
            3 => [
                'Медицинские работники',
                'Педагогические работники',
                'Социальные работники',
                'Психолог',
                'Администрация',
            ],
            4 => [
                'Да',
                'Нет',
            ],
            5 => [
                'Да',
                'Нет',
            ],
        ];

        $result = [];
        foreach ($mas[$index] as $item) {
            $result[$item] = $item;
        }

        return $result;
    }

    public function send()
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose('poll', ['model' => $this])
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
