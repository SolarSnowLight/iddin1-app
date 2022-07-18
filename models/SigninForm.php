<?php

namespace app\models;

use app\models\User;
use yii\base\Model;


class SigninForm extends Model {
    public $username;
    public $password;

    public $check;
    private $_user = false;

    public function rules(){
        return [
            [['username', 'password'], 'required', 'message' => 'Заполните поле'],
            ['username', 'trim'],
            ['password', 'validatePassword'],
            ['check', 'app\components\BotValidator']
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if(!$user) {
                $this->addError($attribute, \Yii::t('account/signin', 'Пользователя с данным логином не существует'));
            }
            
            if ($user && !$user->validatePassword($this->password))
            {
                $this->addError($attribute, \Yii::t('account/signin', 'Неверный пароль, повторите попытку'));
            }
        }
    }

    /* Авторизация пользователя */
    public function login()
    {
        if ($this->validate())
        {
            return \Yii::$app->user->login($this->getUser());
        }
        else
        {
            return false;
        }
    }

    /* Получение информации о пользователе */
    public function getUser()
    {
        if ($this->_user === false)
        {
            $this->_user = User::findByUsername($this->username);
        }

        if (($this->_user === false) || (!($this->_user))){
            $this->_user = User::findByEmail($this->username);
        }

        // null|\yii\web\IdentityInterface|\app\models\User
        return $this->_user;
    }
}