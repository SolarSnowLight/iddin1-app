<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use app\models\User;

class SocietyAnswerList extends \yii\db\ActiveRecord
{
    public $check;

    public static function tableName()
    {
        return '{{%soc_msg_answer}}';
    }


    public static function getAll()
    {
        $data = self::find()->all();
        return $data;
    }
}
