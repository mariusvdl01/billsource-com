<?php

namespace tests\codeception\common\_support;

use Codeception\Module;
use yii\test\FixtureTrait;
use tests\codeception\common\fixtures\UserFixture;
use tests\codeception\common\fixtures\IndividualClientFixture;
use tests\codeception\common\fixtures\BusinessClientFixture;
use tests\codeception\common\fixtures\AuthAssignmentFixture;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * The database is populated with the data, which is used in acceptance and functional tests.  
 * All fixtures will be loaded before the suite is started and unloaded after it completes.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user,
     * business and individual client to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/init_login.php',
            ],
            'business_client' => [
                'class' => BusinessClientFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/init_bus_client.php',
            ],
            'individual_client' => [
                'class' => IndividualClientFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/init_ind_client.php',
            ],
            'auth_assignment' => [
                'class' => AuthAssignmentFixture::className(),
                'dataFile' => '@tests/codeception/common/fixtures/data/init_auth_assignment.php',
            ],
        ];
    }
}
