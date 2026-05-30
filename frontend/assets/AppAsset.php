<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Application asset bundle model
 * 
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
	/**
	 * 
	 * @var string $basePath application base path (such as /var/www...)
	 */
    public $basePath = '@webroot';
    
    /**
     * 
     * @var string $baseUrl application base url (such as http://example.com/)
     */
    public $baseUrl = '@web';
    
    /**
     * 
     * @var array $css relative path where css files is located.
     * This path is relative to the application root directory.
     */
    public $css = [
        'css/site.css',
    ];
    
    /**
     * 
     * @var array $depends path to dependencies required by this asset bundle
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
