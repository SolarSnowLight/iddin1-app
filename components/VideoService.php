<?
namespace app\components;

use yii\db\ActiveRecord;

/**
 * Parsing of links with the video
 * @package app\components
 */
class VideoService extends \yii\base\Behavior
{
    /**
     * @var string Source url
     */
    public $sourceAttribute;

    /**
     * @var string Embded code
     */
    public $destAttribute;

    /**
     * @var string Video image
     */
    public $imageAttribute;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate()
    {
        /** @var ActiveRecord $owner */
        $owner = $this->owner;

        $data = $this->parse($owner->{$this->sourceAttribute});
        if ($data) {
            $owner->{$this->destAttribute} = $data['src'];

            if ($this->imageAttribute && $data['image']) {
                $owner->{$this->imageAttribute} = $data['image'];
            }
        } else {
            $owner->addError($this->sourceAttribute, \Yii::t('admin/main', 'Incorrectly refers to video'));
        }
    }

    private function parse($value)
    {
        $services = [
            'youtube',
            'rutube',
            'vimeo'
        ];

        foreach ($services as $service) {
            $data = $this->$service($value);
            if ($data) {
                return $data;
            }
        }

        return false;
    }

    private function youtube($value)
    {
        if (preg_match('/.*youtube\.com\/watch\?.*v=(.*)(&|\#|$)/U', $value, $matches) || preg_match('/.*youtu.be\/(.*)/', $value, $matches)) {
            if (isset($matches[1])) {
                return [
                    'src' => '//youtube.com/embed/' . $matches[1],
                    'id' => $matches[1],
                    'image' => 'http://img.youtube.com/vi/' . $matches[1] . '/default.jpg'
                ];
            }
        }

        return false;
    }

    private function rutube($video)
    {
        if (preg_match('/.*rutube\.ru\/video\/(.*)(\/|\#|$)/U', $video, $matches)) {
            if (isset($matches[1])) {
                $xml = simplexml_load_file("http://rutube.ru/cgi-bin/xmlapi.cgi?rt_mode=movie&rt_movie_id=" . $matches[1] . "&utf=1");
                if ($xml) {
                    $image = (string)$xml->thumbnail_url;
                }

                return [
                    'src' => '//rutube.ru/embed/' . $matches[1],
                    'id' => $matches[1],
                    'image' => empty($image) ? '' : $image
                ];
            }
        }
        return false;
    }

    private function vimeo($video)
    {
        if (preg_match('/.*vimeo\.com\/(.*)(\/|\#|$)/U', $video, $matches)) {
            if (isset($matches[1])) {
                $xml = simplexml_load_file('http://vimeo.com/api/v2/video/' . $matches[1] . '.xml');
                if ($xml) {
                    $image = (string)$xml->video->thumbnail_small;
                }

                return [
                    'src' => '//player.vimeo.com/video/' . $matches[1],
                    'id' => $matches[1],
                    'image' => empty($image) ? '' : $image
                ];
            }
        }

        return false;
    }
}