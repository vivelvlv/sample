<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/bootstrap.min.css',
        '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        //'plugins/iCheck/flat/blue.css',
        'css/main.css',
    ];
    public $js = [
        //'js/bootstrap.min.js',
        // '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
        //'//code.jquery.com/ui/1.11.4/jquery-ui.min.js',
        //'plugins/sparkline/jquery.sparkline.min.js',
        //'plugins/slimScroll/jquery.slimscroll.min.js',
        //'plugins/fastclick/fastclick.min.js',
        'js/jquery.cookie.js',
        'js/bootbox.js',
        //'js/dashboard.js',
        'js/main.js',
        'js/app.min.js',
        'js/kbModalAjax.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
