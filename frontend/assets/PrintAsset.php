<?php
/**
 * Created by PhpStorm.
 * User: vive
 * Date: 2017/1/18
 * Time: 下午8:40
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class PrintAsset extends DashboardAsset
{
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
        'http://www.jq22.com/jquery/jquery-migrate-1.2.1.min.js',
        'js/jquery.jqprint-0.3.js'
    ];

    public $css = [
    ];
}