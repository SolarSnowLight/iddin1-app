<?

namespace app\components;

class SocietyFormAction extends \yii\base\Action
{
    public $className;

    public $title;

    public function run()
    {
        $model = new $this->className();

        if ($model->load(\Yii::$app->request->post()) && $model->send()){
            $this->controller->redirect(array('success', 'c' => $model->content));
        }

        $this->controller->view->title = $this->title;
        return $this->controller->render('index', ['model' => $model]);
    }
}