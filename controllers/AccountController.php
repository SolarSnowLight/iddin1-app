<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;
use app\models\SigninForm;
use app\models\User;
use app\models\SocietyList;
use app\models\SocietyItem;
use app\models\AnswerForm;
use DateTime;
use yii\data\ActiveDataProvider;

class AccountController extends Controller
{
    /* Функция проверки доступа к панели администратора (модератора) */
    function checkAccess()
    {
        $user = [];
        $userAssigned = \Yii::$app->authManager->getAssignments(\Yii::$app->getUser()->identity->id);
        foreach ($userAssigned as $userAssign) {
            $user[] = $userAssign->roleName;
        }

        return (in_array('admin', $user) || in_array('moderator', $user));
    }

    /* Обработка маршрута начальной страницы */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(array('signin'));
        }

        return $this->render('index');
    }

    /* Обработка маршрута регистрации нового пользователя */
    public function actionSignup()
    {
        /* Если пользователь авторизован, то происходит переадресация на начальную страницу */
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(array('index'));
        }

        /* Создание новой формы */
        $model = new SignupForm();

        /* Загрузка данных из модели и валидация данных модели*/
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->setPassword($model->password);
            $user->setEmail($model->email);
            $user->setActive();
            $user->setDateReg();
            $user->setUsername($model->username);
            $user->generateAuthKey();
            $user->saveData();

            $current_date = date("Y-m-d H:i:s");

            /* Добавление роли пользователю */
            Yii::$app->db->createCommand()->insert('iddi_auth_assignment', [
                'item_name' =>  'user',
                'user_id' => $user->getId(),
                'created_at' => $current_date
            ])->execute();

            /* Добавление информации о пользователе */
            Yii::$app->db->createCommand()->insert('iddi_user_info', [
                'user_id' => $user->getId(),
                'fullname' => $model->surname . $model->name . $model->patronymic,
                'organization' => $model->organization,
                'email' => $model->email,
                'phone' => $model->phone,
                'username' => $model->username,
                'created_at' => $current_date,
                'updated_at' => $current_date
            ])->execute();

            /* Формирование контента для отправки сообщения пользователю */
            $content = '<p>Был создан личный кабинет на сайте iddin1.ru</p>';
            $content = $content . '<p>Login: ' . $model->username . '</p>';
            $content = $content . '<p>Password: ' . $model->password . '</p>';
            $content = $content . '<p>Дата регистрации: ' . (new DateTime())->format('Y-m-d H:i:s') . '</p>';

            /* Сборка Mailer'a */
            $sendM = Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['smtp_email'])
                ->setTo($model->email)
                ->setSubject('Уведомление ' . Yii::$app->params['name_app'])
                ->setHtmlBody($content);

                
            /* Отправка уведомления о завершении регистрации на сайте */
            $sendM->send();

            return $this->redirect(array('index'));
        }

        return $this->render('signup', compact('model'));
    }

    /* Обработка маршрута авторизации пользователя */
    public function actionSignin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(array('index'));
        }

        $model = new SigninForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->login()) {
            return $this->redirect(array('index'));
        }


        return $this->render('signin', compact('model'));
    }

    /* Обработка маршрута выхода из аккаунта */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(array('signin'));
    }

    /* Обработка маршрута просмотра таблицы обращений пользователя */
    public function actionList()
    {
        $query = SocietyList::find()
            ->where(['email' => \Yii::$app->user->identity->email]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('list', ['dataProvider' => $dataProvider]);
    }

    /* Обработка маршрута обновления отдельного обращения пользователя */
    public function actionUpdate($id)
    {
        $model = SocietyList::findOne($id);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->redirect('list');
        }

        return $this->render('update', ['model' => $model]);
    }

    /* Обработка маршрута просмотра обращения пользователя */
    public function actionView($id)
    {
        $model = SocietyList::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    /* Обработка маршрута удаления пользовательского обращения */
    public function actionDelete($id)
    {
        $model = SocietyList::findOne($id);
        if ($model) {
            $model->delete();
        }

        if ($this->checkAccess()) {
            return $this->redirect('admin');
        }

        return $this->redirect('list');
    }

    /* Обработка маршрута вход в панель администратора */
    public function actionAdmin()
    {
        $user = [];
        $userAssigned = \Yii::$app->authManager->getAssignments(\Yii::$app->getUser()->identity->id);
        foreach ($userAssigned as $userAssign) {
            $user[] = $userAssign->roleName;
        }

        if ((in_array('admin', $user) || in_array('moderator', $user))) {
            $query = SocietyList::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ]
            ]);
            return $this->render('admin', ['dataProvider' => $dataProvider]);
        }

        return $this->render('list');
    }

    /* Обработка маршрута ответа на обращение администратором */
    public function actionAnswer($id)
    {
        if ($this->checkAccess()) {
            $model = SocietyList::findOne($id);

            $resultModel = new AnswerForm();
            $resultModel->id_society = $model->id;
            $resultModel->to_email = $model->email;
            
            if ($resultModel->load(\Yii::$app->request->post()) && $resultModel->validate() && $resultModel->send()) {
                $this->redirect('admin');
            }

            return $this->render('answer', ['model' => $resultModel]);
        }

        return $this->render('index');
    }
}
