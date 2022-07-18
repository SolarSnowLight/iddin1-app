<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use mrssoft\engine\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package vova07\users\models
 * User model.
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $secure_key
 * @property integer $status Status
 * @property integer $reg_date
 * @property integer $last_visit
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;
    const STATUS_DELETED = 3;

    const ROLE_DEFAULT = 'user';

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Find users by IDs
     *
     * @param $ids array
     * @param null $scope Query scope
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findIdentities($ids, $scope = null)
    {
        $query = static::find()->where(['id' => $ids]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }

        return $query->all();
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
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPasswordResetToken(){
        return $this->password_reset_roken;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Auth Key validation.
     * @param string $authKey
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Password validation.
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('site', 'Login'),
            'email' => 'E-mail',
            'password' => Yii::t('site', 'Password')
        ];
    }

    public function getRoles()
    {
        $authManager = Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRolesByUser($this->id), 'name', 'name');
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Activates user account.
     *
     * @return boolean true if account was successfully activated
     */
    public function activation()
    {
        $this->status = self::STATUS_ACTIVE;

        return $this->save(false);
    }

    /**
     * Recover password.
     *
     * @param string $password New Password
     * @return boolean true if password was successfully recovered
     */
    public function recovery($password)
    {
        $this->setPassword($password);

        return $this->save(false);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function setDateReg()
    {
        $this->reg_date = date("Y-m-d H:i:s");
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function saveData()
    {
        $this->save(false);
    }

    /**
     * Change user password.
     *
     * @param $password
     * @return boolean true if password was successfully changed
     */
    public function password($password)
    {
        $this->setPassword($password);

        return $this->save(false);
    }

    /* Методы для восстановления пароля */

    /* Поиск токена обновления пароля */
    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /* Проверка валидности токена обновления пароля */
    public static function isPasswordResetTokenValid($token)
    {
        if ((!$token) || empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /* Генерация токена обновления пароля */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /* Удаление токена обновления пароля */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
