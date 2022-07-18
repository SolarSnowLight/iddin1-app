<?php

namespace app\models;

use mrssoft\engine\behaviors\Search;
use mrssoft\multilang\LangBehavior;
use mrssoft\multilang\LangQuery;
use Yii;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property integer $public
 * @property string $date
 *
 * @property Mediaitem[] $items
 * @property Mediaitem $main
 */
class Media extends \mrssoft\engine\ActiveRecord
{
    public $searchAttributes = [
        'type' => false
    ];

    public static function tableName()
    {
        return '{{%media}}';
    }

    public function behaviors()
    {
        return [
            'lang' => [
                'class' => LangBehavior::className(),
                'attributes' => ['title', 'description'],
            ],
        ];
    }

    public static function find()
    {
        $query = new LangQuery(get_called_class());
        $query->localized();
        return $query;
    }

    public function rules()
    {
        return [
            [['title', 'type', 'date'], 'required'],
            [['description'], 'string'],
            [['type'], 'in', 'range' => array_keys(Media::getTypes())],
            [['public'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['title'], 'string', 'max' => 128],
            [['public', 'title', 'id', 'type'], 'safe', 'on' => 'search'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('admin/main', 'Title'),
            'description' => Yii::t('admin/main', 'Description'),
            'type' => Yii::t('admin/main', 'Type'),
            'public' => Yii::t('admin/main', 'Public'),
            'date' => Yii::t('admin/main', 'Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Mediaitem::className(), ['media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMain()
    {
        return $this->hasOne(Mediaitem::className(), ['media_id' => 'id'])
                    ->select(['image', 'path', 'media_id'])
                    ->where([
                        Mediaitem::tableName().'.main' => 1,
                        Mediaitem::tableName().'.public' => 1
                    ]);
    }

    /**
     * Типы медиа
     * @return array
     */
    public static function getTypes()
    {
        return [
            'video' => Yii::t('admin/menu', 'Video'),
            'photo' => Yii::t('admin/menu', 'Photo'),
        ];
    }

    public function getTypeName()
    {
        return self::getTypes()[$this->type];
    }
}
