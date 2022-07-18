<?
namespace app\components;

class UserLog extends \yii\base\Behavior
{
    public $table = '{{%user_log}}';

    private $user_id;

    public function events()
    {
        return [
            User::EVENT_AFTER_LOGIN => 'afterLogin',
            User::EVENT_AFTER_LOGOUT => 'afterLogout',
        ];
    }

    public function afterLogin()
    {
        $this->log('Вход.');
    }

    /**
     * @param \yii\base\Event $event
     */
    public function afterLogout($event)
    {
        $this->user_id = $event->identity->id;
        $this->log('Выход.');
    }

    /**
     * @param mixed $message
     */
    public function log($message)
    {
        $user_id = $this->user_id ? $this->user_id : $this->owner->id;
        if (!empty($user_id))
        {
            \Yii::$app->db->createCommand()->insert($this->table, [
                'user_id' => $user_id,
                'log' => $message
            ])->execute();
        }
    }
}