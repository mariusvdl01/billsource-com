<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 5/5/16
 * Time: 3:42 PM
 */

namespace console\controllers;

use common\models\collector\CollectorsBin as Bin;
use common\models\invoice\Invoice;
use Yii;
use yii\console\Controller;

class CollectorController extends Controller
{
    /**
     * Update collectors bin periodically
     *
     */
    public function actionHandOver()
    {
        $counter = 0;
        $batch = array();
        $totalCounter = 0;
        $bills = Invoice::findAllOverdueBills();

        if (is_array($bills)) {
            $db = Yii::$app->db;
            foreach ($bills as $bill) {
                $invId = $bill['id'];
                $bin = Bin::findOne(['invoice_id' => $invId]);
                if(!$bin) {
                    $now = (new \DateTime('now'))->format('Y-m-d');
                    $batch[] = array(
                        'invoice_id' => $invId,
                        'paid' => $bill['paid'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    );
                    $counter = count(array_keys($batch));
                    if($counter == Bin::BATCH_COUNT) {
                        $db->createCommand()
                            ->batchInsert(Bin::tableName(), Bin::getInsertColumns(), $batch)
                            ->execute();
                        $totalCounter += $counter;
                        $counter = 0;
                        $batch = array();
                    }
                }
            }
            if($counter > 0) {
                $db->createCommand()
                    ->batchInsert(Bin::tableName(), Bin::getInsertColumns(), $batch)
                    ->execute();
            }
        }
        return $totalCounter;
    }
}