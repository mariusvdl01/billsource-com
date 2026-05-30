<?php

namespace console\controllers;

use console\models\Ecosystem;
use yii\console\Controller;

class EcosystemController extends Controller
{
    /**
     * Ecosystem data cruncher
     *
     */
    public function actionCrunchData()
    {
        $ecosystem = new Ecosystem;
        $ecosystem->crunchData();
    }
}
