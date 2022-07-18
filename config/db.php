<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=singapure_iddin1',
    'username' => 'singapure_iddin1',
    'password' => 'A&VAbUJ5',
    'charset' => 'utf8',
    'tablePrefix' => 'iddi_',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 36000000,
    'on afterOpen' => function($event) {
        date_default_timezone_set('Asia/Irkutsk');
        $event->sender->createCommand('SET time_zone = "'.date('P').'"')->execute();
    }
];
