<?php

namespace app\models;

use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{

    public $password;
    public $retry_password;
    public $check;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {

        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Маркер сброса пароля не может быть пустым.');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if (!$this->_user) {
            throw new InvalidParamException('Неверный токен сброса пароля.');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['retry_password', 'validateRetryPassword'],
            ['check', 'app\components\BotValidator']
        ];
    }

    /* Фукнциональные валидаторы */
    public function validateRetryPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (strlen($this->password) < 6) {
                $this->addError($attribute, \Yii::t('account/reset-password', 'Пароль должен состоять не менее из шести символов'));
            }

            if ($this->password != $this->retry_password) {
                $this->addError($attribute, \Yii::t('account/reset-password', 'Пароли не совпадают'));
            }
        }
    }
    /***/

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->password);
            $user->removePasswordResetToken();
            return $user->save(false);
        }
    }
}
