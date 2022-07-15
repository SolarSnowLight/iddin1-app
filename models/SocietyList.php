<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use app\models\User;

class SocietyList extends \yii\db\ActiveRecord
{
    public $check;

    public static function tableName()
    {
        return '{{%soc_msg}}';
    }


    public static function getAll()
    {
        $data = self::find()->all();
        return $data;
    }

    public function rules()
    {
        return [
            [[
                'name', 'email', 'body',
            ], 'required'],
            [['name', 'address', 'email', 'body', 'phone',], 'string'],
            ['email', 'email'],
            ['check', 'app\components\BotValidator']
        ];
    }
}
