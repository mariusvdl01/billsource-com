<?php

namespace tests\codeception\frontend\unit\models;

use Yii;
use tests\codeception\frontend\unit\DbTestCase;
use Codeception\Specify;
use common\models\AuditTrail;

class AuditTrailTest extends DbTestCase
{

    use Specify;

    public function testAuditTrailLogging()
    {
    	$model = new AuditTrail();      
        $ip = Yii::$app->request->getUserIP();

        expect('log inserted into database', 
        		$model->log('1', AuditTrail::className(), __METHOD__, 'Audit Logging works', $ip))->true();
    }
    
    public function testStoreResponseDataIsSaved() {
    	$model = new AuditTrail();
    	
    	expect('response data saved to database', $model->storePaymentResult(basename($_SERVER['SCRIPT_NAME'])))->true();
    	
    }
}