<?php
/**
 * @link http://www.billsource.co.za/
 * @copyright Copyright (c) 2015 Mobyl Systems
 * @license http://www.billsource.co.za/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * This is the matchbox asset bundle model
 * 
 * @author Kenneth Onah <onah.kenneth@gmail.com>
 * @since 1.0
 */
class MatchboxAsset extends AssetBundle
{
	/**
	 * Conents of this path will be copied by the application to a web accessible
	 * folder when it is register in a view script.
	 * 
	 * @var string $sourcePath location of asset bundle files and folders
	 * 
	 */
    public $sourcePath = '@frontend/assets/matchbox';
    
    /**
     *
     * @var array $js relative path where javascript files are located.
     */
    public $js = [
        'js/matchbox.js',
    ];
}
