<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use app\models\User;

/* Модель для формы регистрации нового пользователя */

class UserInfo extends \yii\db\ActiveRecord
{
    /* Атрибуты модели */
    public $check;
    private $_user = false;

    public $reset_password;
    public $password;
    public $retry_password;

    public static function tableName()
    {
        return '{{%user_info}}';
    }

    /* Правила валидации */
    public function rules()
    {
        return [
            [['username', 'email', 'fullname', 'username'], 'required', 'message' => 'Заполните поле'],
            [['organization', 'phone', 'password', 'fullname', 'username', 'retry_password'], 'string'],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+7[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$/', 'message' => 'Номер телефона должен быть формата +7987XXXXXXX' ],
            ['reset_password', 'validateResetPassword'],
            ['check', 'app\components\BotValidator']
        ];
    }

    /* Фукнциональные валидаторы */
    public function validateResetPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->reset_password) {
                if (strlen($this->password) < 6) {
                    $this->addError($attribute, \Yii::t('account/index', 'Пароль должен состоять не менее из шести символов'));
                }

                if ($this->password != $this->retry_password) {
                    $this->addError($attribute, \Yii::t('account/index', 'Пароли не совпадают'));
                }
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

    /**
     * Найти пользователя по имени
     * @param string $username Username
     * @param string|array|null $scope Query scope
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($username, $scope = null)
    {
        $query = static::find()->where(['username' => $username]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }

        return $query->one();
    }

    /**
     * Найти пользователя по email-адресу
     * @param string $email Email
     * @param string|array|null $scope Query scope
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByEmail($email, $scope = null)
    {
        $query = static::find()->where(['email' => $email]);
        if ($scope !== null)
        {
            if (is_array($scope))
            {
                foreach ($scope as $value)
                {
                    $query->$value();
                }
            }
            else
            {
                $query->$scope();
            }
        }

        return $query->one();
    }

    public function updateUserInfo()
    {
        if ($this->validate()) {
            /* Изменение пароля пользователя */
            if ($this->reset_password) {
                $account = User::findByEmail($this->email);

                if (!$account) {
                    $this->addError($attribute, \Yii::t('account/index', 'Аккаунта с данным почтовым адресом не существует'));
                    return;
                }

                $account->setPassword($this->password);
                $account->saveData();
            }

            $this->save(false);

            $this->reset_password = false;
            $this->password = '';
            $this->retry_password = '';

            return true;
        } else {
            return false;
        }
    }
}
