<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use app\models\User;
use DateTime;

class AnswerForm extends Model
{
    public $id_society;
    public $to_email;
    public $fullname;
    public $body;
    public $phone;
    public $files;
    public $check;

    public function rules()
    {
        return [
            [[
                'fullname', 'body', 'to_email',
            ], 'required'],
            [['fullname', 'body', 'phone'], 'string'],
            ['to_email', 'email'],
            [['files'], 'file', 'extensions' => [
                'txt', 'doc', 'docx', 'rtf', 'xls', 'xlsx', 'pps', 'ppt',
                'odt', 'ods', 'odp', 'pub', 'pdf', 'jpg', 'jpeg', 'bmp',
                'png', 'tif', 'gif', 'pcx', 'mp3', 'wma', 'avi', 'mp4',
                'mkv', 'wmv', 'mov', 'flv',
            ], 'maxFiles' => 10],
            ['phone', 'match', 'pattern' => '/^\+7[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/', 'message' => 'Номер телефона должен быть формата +7987XXXXXXX'],
            ['check', 'app\components\BotValidator']
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            $current_date = (new DateTime())->format('Y-m-d H:i:s');
            $content = '<p>Ответ на обращение под номером ' . $this->id_society . '</p>';
            $content = $content . '<p>' . $this->body . '</p>';

            if (!empty($this->phone)) {
                $content = $content . '<p>' . 'Контактные данные: ' . $this->phone . '</p>';
            }

            $content = $content . '<p>Дата ответа на обращение: ' . $current_date . '</p>';
            $content = $content . '<p>С Уважением, ' . $this->fullname . '</p>';

            $sendM = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['smtp_email'])
                ->setTo($this->to_email)
                ->setSubject('Уведомление ' . Yii::$app->params['name_app'])
                ->setHtmlBody($content);

            $filePaths = "";

            /* Загрузка файлов и прикрепление файлов к письму */
            $this->files = UploadedFile::getInstances($this, 'files');
            foreach ($this->files as $file) {
                $filePath = 'uploads/' . $file->baseName . '.' . $file->extension;
                if ($file->saveAs($filePath)) {
                    $filePaths .= $filePath . ";";

                    /* Добавление файлов в сообщение */
                    $sendM->attach($filePath);
                }
            }

            /* Отправка уведомления пользователю */
            $sendM->send();

            /* Внесение данные ответа на обращение в базу данных */

            Yii::$app->db->createCommand()->insert('iddi_soc_msg_answer', [
                'id_soc_msg' =>  $this->id_society,
                'user_id' => \Yii::$app->user->identity->id,
                'to_email' => $this->to_email,
                'fullname' => $this->fullname,
                'body' => $this->body,
                'phone' => $this->phone,
                'files' => $filePaths,
                'created_at' => $current_date,
                'updated_at' => $current_date
            ])->execute();

            return true;
        } else {
            return false;
        }
    }
}
