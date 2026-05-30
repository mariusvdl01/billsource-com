<?php
/**
 * @link http://www.billsource.co.za/
 * @copyright Copyright (c) 2015 Mobyl Systems
 * @license http://www.billsource.co.za/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * This is the ticket page asset bundle model
 * 
 * @author Kenneth Onah <onah.kenneth@gmail.com>
 * @since 1.0
 */
class TicketAsset extends AssetBundle
{
	/**
	 * Conents of this path will be copied by the application to a web accessible
	 * folder when it is register in a view script.
	 * 
	 * @var string $sourcePath location of asset bundle files and folders
	 * 
	 */
    public $sourcePath = '@frontend/assets/ticket';

    
    public $js = [
    	'js/ticket.js',
    ];
    /**
     * If this dependecy has been include by another view script. It will not
     * be include again.
     *
     * @var array $depends path to dependencies require by this asset bundle.
     *
     */
    public $depends = [
    	'yii\web\JqueryAsset',
    ];
}
