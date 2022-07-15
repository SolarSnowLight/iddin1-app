<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use app\models\User;

class AnswerForm extends Model
{
    public $id_society;
    public $to_email;
    public $fullname;
    public $body;
    public $phone;
    public $check;

    public function rules()
    {
        return [
            [[
                'fullname', 'body', 'to_email'
            ], 'required'],
            [['fullname', 'body', 'phone'], 'string'],
            ['check', 'app\components\BotValidator']
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            $content = '<p>Ответ на обращение под номером ' . $this->id_society . '</p>';
            $content = $content . '<p>' . $this->body . '</p>';

            print_r($this->phone);
            if(!empty($this->phone)){
                $content = $content . '<p>' . 'Контактные данные: ' . $this->phone . '</p>';
            }

            $content = $content . '<p>С Уважением, ' . $this->fullname . '</p>'; 
            
            $sendM = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['smtp_email'])
                ->setTo($this->to_email)
                ->setSubject('Уведомление ' . Yii::$app->params['name_app'])
                ->setHtmlBody($content);

            /* Отправка уведомления пользователю */
            $sendM->send();

            return true;
        } else {
            return false;
        }
    }
}
