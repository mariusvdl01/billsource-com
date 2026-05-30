<?php

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\DbTestCase;
use Codeception\Specify;
use tests\codeception\common\fixtures\StatusFixture;

/**
 * Login form test
 */
class StatusTest extends DbTestCase
{

    use Specify;

    public function testGetAllStatuses()
    {
        $model = Yii::createObject('common\models\Status');

        $this->specify('model returns all titles', function () use ($model) {
            expect('model should instance of Status', $model)->isInstanceof('common\models\Status');
            expect('model should return all invoice statuses', $model->findAllStatuses())->notEmpty();
        });
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'status' => [
                'class' => StatusFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/status.php'
            ],
        ];
    }

}
