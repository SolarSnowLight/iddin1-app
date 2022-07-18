<?php

namespace app\models;

use mrssoft\engine\behaviors\MainElement;
use app\components\VideoService;
use mrssoft\engine\behaviors\ImageDemands;
use mrssoft\engine\behaviors\ImageFunctions;
use mrssoft\engine\behaviors\Position;
use mrssoft\multilang\LangBehavior;
use mrssoft\multilang\LangQuery;
use Yii;

/**
 * This is the model class for table "{{%media_item}}".
 *
 * @property string $id
 * @property string $media_id
 * @property string $path
 * @property string $title
 * @property string $description
 * @property string $image
 * @property integer $public
 * @property integer $main
 * @property integer $position
 * @property Media $media
 *
 * @method resize()
 * @method needSize()
 * @method getThumb()
 * @method getImage()
 * @method getImageWidth()
 * @method getImageHeight()
 * @method getImageThumbWidth()
 * @method getImageThumbHeight()
 * @method getQuality()
 * @method getImagePath()
 * @method copy()
 * @method deleteImages()
 * @method thumbPath()
 * @method createThumb()
 */
class Mediaitem extends \mrssoft\engine\ActiveRecord
{
    const IMAGE_WIDTH = 800;
    const IMAGE_HEIGHT = 600;
    const IMAGE_THUMB_WIDTH = 165;
    const IMAGE_THUMB_HEIGHT = 100;
    const IMAGE_QUALITY = 100;

    const FILE_SIZE = 10000000;

    public $relativeAttributes = ['media_id'];

    public $searchAttributes = ['main' => false];

    public static function tableName()
    {
        return '{{%media_item}}';
    }

    public function init()
    {
        parent::init();

        $media_id = Yii::$app->request->get('media_id');
        if ($media_id) {
            $this->media_id = $media_id;
            $this->addBehaviors();
        }
    }

    public function behaviors()
    {
        $behaiors = [
            'lang' => [
                'class' => LangBehavior::className(),
                'attributes' => ['title', 'description'],
                'tableName' => '{{%media_item_lang}}'
            ],
            'position' => [
                'class' => Position::className(),
                'relativeAttributes' => ['media_id']
            ],
            'main' => [
                'class' => MainElement::className(),
                'relativeAttributes' => ['media_id']
            ]
        ];
        return $behaiors;
    }

    private function addBehaviors()
    {
        if (Media::findOne($this->media_id)->type == 'photo') {
            $this->attachBehavior('imageDemands', [
                'class' => ImageDemands::className(),
            ]);
            $this->attachBehavior('imageFunctions', [
                'class' => ImageFunctions::className(),
                'path' => '@web/img/media/{media_id}',
                'attribute' => 'path',
                'width' => self::IMAGE_WIDTH,
                'height' => self::IMAGE_HEIGHT,
                'thumbWidth' => self::IMAGE_THUMB_WIDTH,
                'thumbHeight' => self::IMAGE_THUMB_HEIGHT,
                'quality' => 90
            ]);
        } else {
            $this->attachBehavior('video', [
                'class' => VideoService::className(),
                'sourceAttribute' => 'path',
                'destAttribute' => 'description',
                'imageAttribute' => 'image',
            ]);
        }
    }

    public function afterFind()
    {
        $this->addBehaviors();
        parent::afterFind();
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
            [['media_id', 'path'], 'required'],
            [['media_id', 'public', 'main', 'position'], 'integer'],
            [['description', 'image'], 'string'],
            [['path'], 'string',
                'max' => 256,
                'when' => function ($model) {
                    return Media::findOne($model->media_id)->type == 'video';
                }
            ],
            [['path'], 'image',
                'extensions' => 'jpg,jpeg',
                'maxFiles' => 10,
                'maxSize' => self::FILE_SIZE,
                'when' => function ($model) {
                    return Media::findOne($model->media_id)->type == 'photo';
                }
            ],
            [['title'], 'string', 'max' => 128],
            [['title', 'public', 'id'], 'safe', 'on' => 'search']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => Yii::t('admin/menu', 'Url'),
            'title' => Yii::t('admin/main', 'Title'),
            'description' => Yii::t('admin/main', 'Description'),
            'public' => Yii::t('admin/main', 'Public'),
            'position' => Yii::t('admin/main', 'Position'),
            'main' => Yii::t('admin/menu', 'Main'),
            'image' => Yii::t('admin/main', 'Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'media_id']);
    }

    public function getThumbnail()
    {
        if ($this->media->type == 'photo') {
            return $this->getThumb();
        } else {
            return $this->image;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->media->type == 'photo') {
            $path = '.' . $this->getImage();
            $ih = new \mrssoft\image\ImageHandler();
            $ih->load($path);

            if ($ih->getWidth() > self::IMAGE_WIDTH || $ih->getHeight() > self::IMAGE_HEIGHT) {
                $ih->adaptiveThumb(self::IMAGE_WIDTH, self::IMAGE_HEIGHT)->
                save($path, null, self::IMAGE_QUALITY);
            }

            $ih->adaptiveThumb(self::IMAGE_THUMB_WIDTH, self::IMAGE_THUMB_HEIGHT)->
            save('.' . $this->getThumb());
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
