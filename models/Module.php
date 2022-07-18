<?php

namespace app\models;

use mrssoft\multilang\LangBehavior;
use mrssoft\multilang\LangQuery;
use Yii;

/**
 * This is the model class for table "{{%module}}".
 *
 * @property string $id
 * @property string $name
 * @property string $content
 */
class Module extends \mrssoft\engine\ActiveRecord
{

    public $searchAttributes = [
        'name' => true
    ];

    public static function tableName()
    {
        return '{{%module}}';
    }

    public function behaviors()
    {
        return [
            'lang' => [
                'class' => LangBehavior::className(),
                'attributes' => ['content'],
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
            [['name', 'content'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'match', 'pattern' => '/^[-A-Za-z0-9_]+$/u', 'message' => "{attribute} может содержать только латинские буквы, цифры, и '-', '_'."],
            [['name', 'id'], 'safe', 'on' => 'search']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('admin/menu', 'Name'),
            'content' => Yii::t('admin/menu', 'Content'),
        ];
    }

    /**
     * Содержимое модуля
     * @param $name
     * @return string
     */
    public static function getContent($name)
    {
        $model = self::find()->select('content')->where('name=:name', [':name' => $name])->one();
        if (empty($model))
        {
            return '';
        }

        return $model->getAttribute('content');
    }
}
