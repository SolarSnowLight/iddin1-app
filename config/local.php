<?php

$config = [
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'mail' => [
			 'class' => 'yii\swiftmailer\Mailer',
			 'transport' => [
				 'class' => 'Swift_SmtpTransport',
				 'host' => 'smtp.timeweb.ru',
				 'username' => 'noreplay@iddin1.ru',
				 'password' => 'xvbHWtoG',
			 ],
		],	
    ],
	'params' => [
		'noreplayEmail' => ['noreplay@iddin1.ru'],
        'feedbackEmail' => ['iddin1@mail.ru'],
        'societyMsgEmail' => ['iddin1@mail.ru']
	]
];

return $config;
