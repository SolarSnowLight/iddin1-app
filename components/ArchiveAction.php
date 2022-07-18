<?

namespace app\components;

use app\models\News;
use yii\data\ActiveDataProvider;

class ArchiveAction extends \yii\base\Action
{
    public function run($year, $month = null)
    {
        $query = News::active()->where('YEAR(date) = :year', [':year' => $year])
                               ->orderBy(['date' => SORT_DESC]);

        if ($month) {
            $query->andWhere('MONTH(date) = :month', [':month' => $month]);
        }

        return $this->controller->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                    'pageSizeParam' => false,
                ]
            ])
        ]);
    }
}