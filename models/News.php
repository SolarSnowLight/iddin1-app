<?php

namespace app\models;

use mrssoft\engine\behaviors\MaterialThumb;
use mrssoft\multilang\LangBehavior;
use mrssoft\multilang\LangQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property integer $public
 * @property string $date
 * @property string $title
 * @property string $text
 * @property string $image
 *
 * @property string $preview
 */
class News extends \mrssoft\engine\ActiveRecord
{
    const THUMB_WIDTH = 263;

    public static function tableName()
    {
        return '{{%news}}';
    }

    public function behaviors()
    {
        return [
            'lang' => [
                'class' => LangBehavior::className(),
                'attributes' => ['title', 'text'],
            ],
            'materialThumb' => [
                'class' => MaterialThumb::className(),
                'thumbWidth' => self::THUMB_WIDTH,
                'thumbHeight' => false,
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
            [['public'], 'integer'],
            [['date', 'title', 'text'], 'required'],
            [['title', 'text'], 'trim'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 128],
            [['public', 'title', 'id'], 'safe', 'on' => 'search'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'public' => Yii::t('main', 'Public'),
            'date' => Yii::t('admin/main', 'Date'),
            'title' => Yii::t('main', 'Title'),
            'text' => Yii::t('main', 'Text'),
            'image' => Yii::t('main', 'Image'),
        ];
    }

    /**
     * Анонс новости
     * @return string
     */
    public function getPreview()
    {
        $n = strpos($this->text, '<div style="page-break-after');

        if ($n === false) {
            return StringHelper::truncateWords(strip_tags($this->text, '<p><br>'), 20);
        } else {
            $text = substr($this->text, 0, $n);
            return strip_tags($text, '<p><br>');
        }
    }

    /**
     * Последнии новости
     * @param int $count
     * @return ActiveDataProvider
     */
    public static function last($count = 5)
    {
        $query = self::active()->limit($count)->orderBy(['date' => SORT_DESC]);
        return new ActiveDataProvider(['query' => $query, 'pagination' => false]);
    }
}
