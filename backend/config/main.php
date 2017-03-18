<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    // 'on beforeRequest' => function ($event) {
    //     $l_saved = null;
    
    //      # use cookie to store language
    //     $l_saved = Yii::$app->request->cookies->get('locale');

    //     $l = ($l_saved)?$l_saved:'zh-CN';

    //     Yii::$app->sourceLanguage = 'en';
    //     Yii::$app->language = $l;
    //     return; 
    // }, 
    'bootstrap' => ['log'],
    'modules' => [
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            // other module settings
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
            ]
    ],
    'components' => [
        // 'urlManager' => [      
        //      'enablePrettyUrl' => true,      
        //      'showScriptName' => false, 
        //      'suffix'=>'.html',     
        //      'rules'=>[          
        //     ],
        // ],
        'user' => [
            'identityClass' => 'common\models\AdminUser',
            'enableAutoLogin' => true,
            'idParam'=>'_admin'
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],
    'params' => $params,
];
