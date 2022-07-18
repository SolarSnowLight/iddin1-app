<?php

namespace app\models;

use mrssoft\engine\behaviors\Position;
use Yii;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property string $id
 * @property string $title
 * @property string $url
 * @property integer $public
 * @property string $level
 * @property string $position
 */
class Menu extends \mrssoft\engine\ActiveRecord
{
    public $searchAttributes = [
        'url' => true
    ];

    public static function tableName()
    {
        return '{{%menu}}';
    }

    public function behaviors()
    {
        return [
            'position' => [
                'class' => Position::className()
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'url', 'level'], 'required'],
            [['public', 'level', 'position'], 'integer'],
            [['title'], 'string', 'max' => 64],
            [['url'], 'string', 'max' => 256],
            [['id', 'title', 'level', 'url'], 'safe', 'on' => 'search']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'url' => 'Ссылка',
            'public' => 'Опубликовано',
            'level' => 'Уровень',
            'position' => 'Позиция',
        ];
    }
}
