<?php
use mrssoft\multilang\Lang;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\grid\GridView;

function checkAccess(){
    $user = [];
    $userAssigned = \Yii::$app->authManager->getAssignments(\Yii::$app->getUser()->identity->id);
    foreach ($userAssigned as $userAssign) {
        $user[] = $userAssign->roleName;
    }

    return (in_array('admin', $user) || in_array('moderator', $user));
}

$check = checkAccess();

echo Nav::widget([
    'options' => ['class' => 'navbar-inverse navbar-right'],
    'items' => [
        [
            'label' => \Yii::t('account', 'Logout') . ' (' . \Yii::$app->user->identity->username . ')',
            'url' => ['account/logout'],
            'linkOptions' => ['data-method' => 'post']
        ],
        (!$check) ? [
            'label' => \Yii::t('account', 'Список обращений'),
            'url' => ['account/list'],
            'linkOptions' => ['data-method' => 'post']
        ] : '',
        $check ? [
            'label' => \Yii::t('account', 'Список обращений'),
            'url' => ['account/admin'],
            'linkOptions' => ['data-method' => 'post']
        ] : ''
    ],
]);

echo Html::tag('h1', 'Список заявок', ['class' => 'title-main']);
echo Html::tag('br');
echo Html::tag('br');

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id:text:Номер обращения',
        'name:text:ФИО',
        'phone:text:Номер телефона',
        'address:text:Организация',
        ['class' => 'yii\grid\ActionColumn']
    ]
]);