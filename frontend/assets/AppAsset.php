<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //'css/style.css?v=1',
        'css/main-menu.css?v=31',
        'css/profile-menu.css?v=17',
        'css/animate.css?v=1',
        'ajax/libs/font-awesome/5.15.4/css/all.min.css',
    ];
    public $js = [
        //'npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
		//'js/all.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
