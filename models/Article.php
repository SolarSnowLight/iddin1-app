<?php

namespace app\models;

/**
 * This is the model class for table "{{%work}}".
 *
 * @property string $id
 * @property string $title
 * @property string $type
 * @property string $parent_id
 * @property string $text
 * @property integer $public
 * @property string $date
 * @property string $position
 *
 * @property Article $parent
 * @property Article[] $works
 */
class Article extends \mrssoft\engine\ActiveRecord
{
    public $relativeAttributes = ['parent_id'];

    public $defaultOrder = [
        'type' => SORT_ASC,
        'position' => SORT_ASC
    ];

    public static function tableName()
    {
        return '{{%article}}';
    }

    public function behaviors()
    {
        return [
            'position' => [
                'class' => \mrssoft\engine\behaviors\Position::className(),
                'relativeAttributes' => ['parent_id']
            ],
            'tree' => [
                'class' => \app\components\TreeBehavior::className(),
            ]
        ];
    }

    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['type', 'text'], 'string'],
            [['parent_id', 'public', 'position'], 'integer'],
            [['date'], 'safe'],
            [['date', 'title', 'position', 'id'], 'safe', 'on' => 'search'],
            [['title'], 'string', 'max' => 128]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'type' => 'Тип',
            'parent_id' => 'Родительский элемент',
            'text' => 'Текст',
            'public' => 'Опубликовано',
            'date' => 'Дата',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (empty($this->parent_id))
        {
            $this->parent_id = null;
        }

        return parent::beforeValidate();
    }
}
