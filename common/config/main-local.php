<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=118.190.45.139;dbname=sample',
            'username' => 'sample',
            'password' => 'sample2017',
            // 'dsn' => 'mysql:host=localhost;dbname=sample',
            // 'username' => 'root',
            // 'password' => '123456',
             'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            // 163 
            // 'transport' => [  
            //    'class' => 'Swift_SmtpTransport',  
            //    'host' => 'smtp.163.com',  //每种邮箱的host配置不一样
            //    'username' => 'zhoudeji2006@163.com',  
            //    'password' => 'newfuture110',  
            //    'port' => '25',  
            //    'encryption' => 'tls',  
                                   
            // ],   
            // 'messageConfig'=>[  
            //    'charset'=>'UTF-8',  
            //    'from'=>['zhoudeji2006@163.com'=>'admin']  
            // ],  

            //qq

            'transport' => [  
               'class' => 'Swift_SmtpTransport',  
               'host' => 'smtp.qq.com',  //每种邮箱的host配置不一样
               'username' => '2938526521@qq.com',  
               'password' => 'egibgjxofviwdfhj',  
               'port' => '25',  
               'encryption' => 'tls',  
                                   
            ],   
            'messageConfig'=>[  
               'charset'=>'UTF-8',  
               'from'=>['2938526521@qq.com'=>'admin']  
            ], 

        ],
        'upload' => [
            'class' => 'backend\components\UploadComponent',
            'imageRootPath' => dirname(__DIR__) . '/web',
           // 'imageRootUrl' => 'http://localhost/sample/sample/common/web/',
            'imageRootUrl'=>'http://118.190.45.139:8082/',
            'fileRootPath' => dirname(__DIR__) . '/web',
            //'fileRootUrl' => 'http://localhost/sample/sample/common/web/',
            'fileRootUrl'=>'http://118.190.45.139:8082/',
        ],
    ],
];
