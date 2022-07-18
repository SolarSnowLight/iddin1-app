<?

namespace app\controllers;

use yii\base\Controller;

class SearchController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Результаты поиска';
        return $this->render('index');
    }
}