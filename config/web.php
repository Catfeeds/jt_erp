<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'language'=>'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@imgDir' => dirname(__DIR__).'/web/upload/image',
        '@imgPath' => '/upload/image'
    ],
    'modules' => [
        'gridview'=>[ 'class' => 'kartik\grid\Module',
                'downloadAction'=>'/orders/download'
            ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',//yii2-admin的导航菜单
        ]
    ],

'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Cr61w9nIKKS0nDDhgl0AqALLuynmHkGO',
        ],
        'storage' => [
            'class' => 'weyii\filesystem\Manager',
            'default' => 'qiniu',
            'disks' => [
                'local' => [
                    'class' => 'weyii\filesystem\adapters\Local',
                    'root' => '@webroot/storage' // 本地存储路径
                ],
                'fdfs' => [
                    'class' => 'app\libs\FDFS',
                    'baseUrl' => 'http://cdn.kingdomskymall.net/fdfs_upload.php',
                    'salt' => 'salt001',
                    'fileField' => 'fdfs_file',
                ],
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'mdm\admin\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
//        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'shop/<host>' => 'shop/index',
                'shop/api/<host>' => 'shop/api',
                'shop/add/order' => 'shop/add-order',
        ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'zh-CN',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],

    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
//            'site/*',//允许访问的节点，可自行添加
//            'admin/*',//允许所有人访问admin节点及其子节点
            'gii/*',
            'shop/*',
            'country/*'
        ]
    ],
    'params' => $params,
    // 'catchAll' => ['site/index'],// 开启路由,在维护的时候，可以让所有页面都跳转到特定页面
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
        'allowedIPs' => ['*'],
        'generators' => [ //here
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'adminlte' => '@vendor/dmstr/yii2-adminlte-asset/gii/templates/crud/simple',
                ]
            ]
        ],
    ];
}

return $config;
