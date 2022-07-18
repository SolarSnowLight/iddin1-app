<?php

namespace app\models;

use mrssoft\multilang\LangBehavior;
use mrssoft\multilang\LangQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%hap_page}}".
 *
 * @property string $id
 * @property integer $public
 * @property integer $parent_id
 * @property string $url
 * @property string $title
 * @property string $text
 *
 * @property Page $parent
 */
class Page extends \mrssoft\engine\ActiveRecord
{
    public $searchAttributes = [
        'url' => true
    ];

    public static function tableName()
    {
        return '{{%page}}';
    }

    public function behaviors()
    {
        return [
            'lang' => [
                'class' => LangBehavior::className(),
                'attributes' => ['title', 'text'],
            ]
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
            [['url', 'title', 'text'], 'required'],
            [['public', 'parent_id'], 'integer'],
            [['title', 'text', 'url'], 'trim'],
            [['url'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 128],
            [['text'], 'string'],
            [['id', 'title', 'public'], 'safe', 'on' => 'search']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'public' => Yii::t('main', 'Public'),
            'url' => Yii::t('main', 'Url'),
            'title' => Yii::t('main', 'Title'),
            'text' => Yii::t('main', 'Text'),
            'parent_id' => Yii::t('site', 'Parent page'),
        ];
    }

    /**
     * Найти страницу по адресу
     * @param $url
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUrl($url)
    {
        return self::find()->where('url=:url', [':url' => $url])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public static function getAllParents()
    {
        $pages = self::find()->select(['id', 'title'])->where(['public' => 1])->orderBy(['title' => SORT_ASC])->all();
        $result = ArrayHelper::map($pages, 'id', 'title');
        return ArrayHelper::merge([0 => '[ нет ]'], $result);
    }

    public function beforeSave($insert)
    {
        if (empty($this->parent_id)) {
            $this->parent_id = null;
        }

        return parent::beforeSave($insert);
    }
}
