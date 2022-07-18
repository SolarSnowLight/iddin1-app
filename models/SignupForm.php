<?php

namespace app\models;

use app\models\User;
use yii\base\Model;

/* Модель для формы регистрации нового пользователя */
class SignupForm extends Model {
    /* Атрибуты модели */
    public $username;
    public $name;
    public $surname;
    public $patronymic;
    public $organization;
    public $phone;
    public $email;
    public $password;
    public $retry_password;
    
    public $is_patronymic_none;
    public $check;
    private $_user = false;

    /* Правила валидации */
    public function rules(){
        return [
            [['username', 'email', 'password', 'name', 'surname', 'retry_password'], 'required', 'message' => 'Заполните поле'],
            [['organization', 'phone', 'password', 'retry_password', 'patronymic'], 'string'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'validateEmail'],
            ['username', 'trim'],
            ['username', 'validateUsername'],
            ['is_patronymic_none', 'boolean'],
            ['is_patronymic_none', 'validatePatronymic'],
            ['retry_password', 'validateRetryPassword'],
            ['check', 'app\components\BotValidator']
        ];
    }

    /* Фукнциональные валидаторы */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if($user) {
                $this->addError($attribute, \Yii::t('account/signup', 'Пользователь с данным логином уже существует'));
            }
        }
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUserByEmail();

            if($user) {
                $this->addError($attribute, \Yii::t('account/signup', 'Пользователь с данным почтовым адресом уже существует'));
            }
        }
    }

    public function validatePatronymic($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->is_patronymic_none) {
                if (empty($this->patronymic)) {
                    $this->addError($attribute, \Yii::t('account/signup', 'Необходимо ввести отчество'));
                }
            }
        }
    }

    public function validateRetryPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->retry_password != $this->password) {
                $this->addError($attribute, \Yii::t('society', 'Пароли должны совпадать'));
            }
        }
    }
    /***/

    /* Контент для label элементов */
    public function attributeLabels()
    {
        return [
            'surname' => "Фамилия *",
            'name' => 'Имя *',
            'patronymic' => 'Отчество (при наличии) *',
            'organization' => 'Наименование организации (юридического лица)',
            'phone' => 'Телефон',
            'email' => 'Email-адрес *',
            'theme' => 'Тема обращения',
            'body' => 'Напишите текст обращения',
            'files' => 'Прикрепить файл(ы)',
            'is_patronymic_none' => 'Отсутствует отчество',
            'create_cabinet' => 'Создать личный кабинет',
            'password' => 'Пароль (не менее шести символов) *',
            'retry_password' => 'Повторите пароль *'
        ];
    }

    /* Поиск пользователя по логину */
    public function getUser()
    {
        $this->_user = User::findByUsername($this->username);

        //null|\yii\web\IdentityInterface|\app\models\User
        return $this->_user;
    }

     /* Поиск пользователя по email-адресу */
    public function getUserByEmail()
    {
        $this->_user = User::findByEmail($this->email);

        // null|\yii\web\IdentityInterface|\app\models\User
        return $this->_user;
    }
}