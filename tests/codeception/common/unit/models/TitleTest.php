<?php

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\DbTestCase;
use Codeception\Specify;
use tests\codeception\common\fixtures\TitleFixture;

/**
 * Login form test
 */
class TitleTest extends DbTestCase
{

    use Specify;

    public function testGetAllTitles()
    {
        $model = Yii::createObject('common\models\Title');

        $this->specify('model returns all titles', function () use ($model) {
            expect('model should instance of Title', $model)->isInstanceof('common\models\Title');
            expect('model should return all titles', $model->findAllTitles())->notEmpty();
        });
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'title' => [
                'class' => TitleFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/title.php'
            ],
        ];
    }

}
