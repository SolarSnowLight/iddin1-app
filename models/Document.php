<?php

namespace app\models;

use dosamigos\transliterator\TransliteratorHelper;
use Yii;

/**
 * This is the model class for table "{{%document}}".
 *
 * @property string $id
 * @property string $title
 * @property string $type
 * @property string $mode
 * @property string $parent_id
 * @property string $path
 * @property integer $public
 * @property string $position
 *
 * @property \mrssoft\engine\behaviors\ImageFunctions $image
 *
 * @property Document $parent
 * @property Document[] $documents
 */
class Document extends \mrssoft\engine\ActiveRecord
{
    const FILE_PATH = '/img/documents/';

    public $relativeAttributes = [
        'parent_id',
        'mode'
    ];

    public static function tableName()
    {
        return '{{%document}}';
    }

    public function behaviors()
    {
        return [
            'position' => [
                'class' => \mrssoft\engine\behaviors\Position::className(),
                'relativeAttributes' => ['mode', 'parent_id']
            ],
            'tree' => [
                'class' => \app\components\TreeBehavior::className(),
            ],
            'file' => [
                'class' => \mrssoft\engine\behaviors\File::className(),
                'attribute' => 'path',
            ],
            'image' => [
                'class' => \mrssoft\engine\behaviors\ImageFunctions::className(),
                'path' =>  '@web/'.self::FILE_PATH,
                'attribute' => 'path',
                'width' => 1280,
                'height' =>  1280,
                'thumbWidth' => 128,
                'thumbHeight' => 128,
                'quality' => 90
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'type', 'mode'], 'required'],
            [['mode'], 'in', 'range' => array_keys(self::getModes())],
            [['type'], 'string'],
            [['parent_id', 'public', 'position'], 'integer'],
            [['path'], 'file', 'extensions' => 'jpg,jpeg,doc,docx,png,zip,pdf'],
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
            'path' => 'Изображение',
            'public' => 'Опубликовано',
            'position' => 'Позиция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Document::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['parent_id' => 'id']);
    }

    public function beforeValidate()
    {
        if (empty($this->parent_id))
        {
            $this->parent_id = null;
        }

        return parent::beforeValidate();
    }

    public function getPath()
    {
        return Yii::getAlias('@webroot/'.self::FILE_PATH).$this->path;
    }

    public function getName()
    {
        $this->title = TransliteratorHelper::process($this->title);
        $this->title = preg_replace('/[^A-Za-zА-Яа-я0-9\s\-]/', ' ', $this->title);
        $this->title = preg_replace('/\s+/', ' ', $this->title);
        $this->title = preg_replace('/\s/', '-', $this->title);
        $this->title = mb_strtolower(trim($this->title, '-'));
        $ext = pathinfo($this->path, PATHINFO_EXTENSION);

        return $this->title.'.'.$ext;
    }

    public function getImage()
    {
        return $this->getBehavior('image');
    }

    public static function getModes()
    {
        return [
            'library' => 'Библиотека документов',
            'license' => 'Лицензии',
            'ustav' => 'Электронные образцы документов учреждения',
            'struct' => 'Структура учреждения',
            'uspehi' => 'Успехи и достижения',
            'rules' => 'Предоставление платных услуг',
            'results' => 'Результаты проверок надзорными органами',
            'cost' => 'Независимая оценка качества оказания социальных услуг',
            'fin' => 'Финансово-хозяйственная деятельность',
            'fin_lto' => 'Финансово-хозяйственная деятельность',
            'corruption' => 'Противодействие коррупции',
            'board_trustees' => 'Попечительский совет',
            'directional' => 'Порядок предоставления социальных услуг',
			'elements' => 'Доступность элементов',
          	'Morjata' => 'Проект моржата',
            'Ustroistvo' => 'Устройство в семью',
          	'Prinyat'=>'Принять участие в опросе',
          	'Ocenka'=>'Оценка качества предоставления социальных услуг',
          	'Rezult'=> 'Результаты опросов',
          	'Obrach'=> 'Обращения граждан',
            'StrPsih'=> 'Страничка психолога',
          
        ];
    }

    public static function getModeName($value)
    {
        return self::getModes()[$value];
    }
}
