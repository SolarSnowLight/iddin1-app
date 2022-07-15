<?

namespace app\components;

class SuccessAction extends \yii\base\Action
{
    public $title;

    public function run()
    {
        $this->controller->view->title = $this->title;
        return $this->controller->render('/layouts/success');
    }
}