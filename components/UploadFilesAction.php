<?
namespace app\components;

use yii\base\Action;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Загрузка файлов
 *
 * @property string $error
 *
 */
class UploadFilesAction extends Action
{
    /**
     * Путь для загрузки изображений
     */
    var $path;

    /**
     * Класс модели
     */
    var $modelClass;

    /**
     * Атрибут модели для имени файла
     */
    var $attribute = 'image';

    /**
     * Дополнительные атрибуты модели
     */
    var $attributes = [];

    var $_error = null;

    /**
     *
     */
    public function run()
    {
        /** @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass();
        $model->{$this->attribute} = UploadedFile::getInstances($model, $this->attribute);

        if ($model->validate([$this->attribute])) {
            $this->preparePath($model);

            foreach ($model->{$this->attribute} as $file) {
                $name = $this->getUniqueFilename();

                /** @var $model \yii\db\ActiveRecord */
                $model = new $this->modelClass();
                $model->{$this->attribute} = $name;
                foreach ($this->attributes as $param => $value) {
                    $model->{$param} = $value;
                }

                if ($model->validate($this->attributes)) {
                    $file->saveAs($this->path . $name);
                    $model->save(false);
                } else {
                    $this->getModelErrors($model);
                    break;
                }
            }
        } else {
            $this->getModelErrors($model);
        }

        if (method_exists($this->controller, 'afterUpload')) {
            $this->controller->afterUpload($this);
        }
    }

    private function getModelErrors($model)
    {
        $this->_error = Html::errorSummary($model, ['header' => false, 'footer' => 'false']);
    }

    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param \yii\db\ ActiveRecord $model
     * @return bool
     */
    private function preparePath($model)
    {
        $method = 'getImagePath';
        if (empty($this->path) && $model->hasMethod($method)) {
            $this->path = $model->{$method}();
        }

        $this->path = \Yii::getAlias($this->path);
        $this->path = rtrim($this->path, '/') . '/';
        $this->path = '.' . ltrim($this->path, '.');

        $parts = explode('/', $this->path);
        $p = '';
        foreach ($parts as $part) {
            $p .= $part . '/';
            if (!file_exists($p)) {
                if (!mkdir($p)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Получить уникальное имя файла в папке
     * @param string $ext
     * @return string
     */
    public function getUniqueFilename($ext = '.jpg')
    {
        do {
            $name = md5(uniqid()) . $ext;
        } while (file_exists($this->path . $name));

        return $name;
    }
}