<?
namespace app\components;

/**
 * Пользователь
 *
 * @method log()
 */
class User extends \yii\web\User
{
    /**
     * @var \app\models\User
     */
    private $_model = null;

    public function behaviors()
    {
        return [
            'logBehavior' => \app\components\UserLog::className()
        ];
    }

    public function afterLogin($identity, $cookieBased, $duration)
    {
        $this->getModel();
        $this->_model->last_visit = date('c');
        $this->_model->save();

        parent::afterLogin($identity, $cookieBased, $duration);
    }

    public function getModel()
    {
        if ($this->_model === null)
        {
            $this->_model = \app\models\User::findOne($this->id);
        }
        return $this->_model;
    }
}