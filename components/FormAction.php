<?

namespace app\components;

class FormAction extends \yii\base\Action
{
    public $className;

    public $title;

    public function run()
    {
        $model = new $this->className();

        if ($model->load(\Yii::$app->request->post()) && $model->send())
        {
            $this->controller->redirect(['success']);
        }

        $this->controller->view->title = $this->title;
        return $this->controller->render('index', ['model' => $model]);
    }
}