<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use app\models\User;
use DateTime;

class SocietyForm extends Model
{
    public $director;
    public $surname;
    public $name;
    public $patronymic;
    public $is_patronymic_none;

    public $email;
    public $retry_email;
    public $phone;
    public $organization;

    public $theme;
    public $body;
    public $files;
    public $create_cabinet;
    public $id;

    public $password;
    public $retry_password;

    public $check;

    public $content;

    public $verifyCode;

    public function rules()
    {
        return [
            [[
                'surname', 'name', 'email',
                'body', 'retry_email',
            ], 'required'],
            [['organization', 'body', 'phone', 'password', 'retry_password', 'patronymic'], 'string'],
            ['email', 'email'],
            ['retry_email', 'email'],
            [['files'], 'file', 'extensions' => [
                'txt', 'doc', 'docx', 'rtf', 'xls', 'xlsx', 'pps', 'ppt',
                'odt', 'ods', 'odp', 'pub', 'pdf', 'jpg', 'jpeg', 'bmp',
                'png', 'tif', 'gif', 'pcx', 'mp3', 'wma', 'avi', 'mp4',
                'mkv', 'wmv', 'mov', 'flv',
            ], 'maxFiles' => 10],
            ['create_cabinet', 'boolean'],
            ['is_patronymic_none', 'boolean'],
            ['phone', 'match', 'pattern' => '/^\+7[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/', 'message' => 'Номер телефона должен быть формата +7987XXXXXXX' ],
            ['retry_email', 'validateRetryEmail'],
            ['is_patronymic_none', 'validatePatronymic'],
            ['create_cabinet', 'validateCreateCabinet'],
            ['verifyCode', 'captcha', 'captchaAction' => 'society/captcha'],
            ['check', 'app\components\BotValidator']
        ];
    }

    public function validateRetryEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->retry_email != $this->email) {
                $this->addError($attribute, \Yii::t('society', 'Email-адреса должны совпадать'));
            }
        }
    }

    public function validatePatronymic($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->is_patronymic_none) {
                if (empty($this->patronymic)) {
                    $this->addError($attribute, \Yii::t('society', 'Необходимо ввести отчество'));
                }
            }
        }
    }

    public function validateCreateCabinet($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->create_cabinet) {
                if (strlen($this->password) < 6) {
                    $this->addError($attribute, \Yii::t('society', 'Пароль должен состоять не менее из шести символов'));
                }

                if ($this->password != $this->retry_password) {
                    $this->addError($attribute, \Yii::t('society', 'Пароли не совпадают'));
                }
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'surname' => "Фамилия *",
            'name' => 'Имя *',
            'patronymic' => 'Отчество (при наличии) *',
            'organization' => 'Наименование организации (юридического лица)',
            'phone' => 'Телефон',
            'email' => 'Email-адрес *',
            'retry_email' => 'Повтор email-адреса *',
            'theme' => 'Тема обращения',
            'body' => 'Напишите текст обращения',
            'files' => 'Прикрепить файл(ы)',
            'is_patronymic_none' => 'Отсутствует отчество',
            'create_cabinet' => 'Создать личный кабинет',
            'password' => 'Пароль (не менее шести символов) *',
            'retry_password' => 'Повторите пароль *'
        ];
    }

    public function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function send()
    {
        if ($this->validate()) {
            $this->content = '';
            $current_date = (new DateTime())->format('Y-m-d H:i:s');

            /* Создание личного кабинета */
            if ($this->create_cabinet) {
                $account = User::findByEmail($this->email);
                if ($account) {
                    $this->addError($attribute, \Yii::t('society', 'Аккаунт с данным почтовым адресом уже существует'));
                    return;
                }

                $login = explode('@', $this->email)[0] . $this->generateRandomString(6);

                $user = new User();
                $user->setPassword($this->password);
                $user->setEmail($this->email);
                $user->setActive();
                $user->setDateReg();
                $user->setUsername($login);
                $user->generateAuthKey();
                $user->saveData();

                Yii::$app->db->createCommand()->insert('iddi_auth_assignment', [
                    'item_name' =>  'user',
                    'user_id' => $user->getId(),
                    'created_at' => $current_date
                ])->execute();

                $this->content = '<p>Был создан личный кабинет на сайте iddin1.ru</p>';
                $this->content = $this->content . '<p>Login: ' . $login . '</p>';
                $this->content = $this->content . '<p>Password: ' . $this->password . '</p>';
            }

            /* Вычисление номера обращения */
            $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM iddi_soc_msg')
                ->queryScalar();
            $this->id = $count + 1;

            /* Отправка обращения */
            /*$sendM = Yii::$app->mailer->compose('society_mail', ['model' => $this])
                ->setTo(Yii::$app->params['societyMsgEmail'])
                ->setFrom(Yii::$app->params['noreplayEmail'])
                ->setSubject('На сайте ' . Yii::$app->name . ' было создано обращение');*/

            $this->content = $this->content . '<p>На сайте ' . Yii::$app->params['name_app'] . ' было создано обращение. Номер обращения: ' . $this->id . '</p>';
            $this->content = $this->content . '<p>Дата обращения: ' . $current_date . '</p>';

            $text_to_email = '<p>На сайте ' . Yii::$app->params['name_app'] . ' было создано обращение. Номер обращения: ' . $this->id . '</p>';
            $text_to_email = $text_to_email . '<p>' . $this->body . '</p>';
            $parce = explode('@', $this->email);
            $text_to_email = $text_to_email . '<p>' . $parce[0] . '@' . $parce[1] . '</p>';
            $text_to_email = $text_to_email . '<p>Дата обращения: ' . $current_date . '</p>';

            $sendM = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['smtp_email'])
                ->setTo($this->email)
                ->setSubject('Уведомление ' . Yii::$app->params['name_app'])
                ->setHtmlBody($this->content);

            $sendA = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['smtp_email'])
                ->setTo(Yii::$app->params['feedbackEmail'])
                ->setSubject('Уведомление ' . Yii::$app->params['name_app'])
                ->setHtmlBody($text_to_email);

            /* Переменная для хранения путей к файлам */
            $filePaths = "";

            /* Загрузка файлов и прикрепление файлов к письму */
            $this->files = UploadedFile::getInstances($this, 'files');
            foreach ($this->files as $file) {
                $filePath = 'uploads/' . $file->baseName . '.' . $file->extension;
                if ($file->saveAs($filePath)) {
                    $filePaths .= $filePath . ";";

                    /* Добавление файлов в сообщение */
                    $sendM->attach($filePath);
                    $sendA->attach($filePath);
                }
            }

            /* Отправка уведомления пользователю */
            $sendM->send();
            $sendA->send();


            /* Сохранение обращения в БД */
            Yii::$app->db->createCommand()->insert('iddi_soc_msg', [
                'name' =>  $this->surname . ' ' . $this->name . ' ' . $this->patronymic,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->organization,
                'theme' => '',
                'body' => $this->body,
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
