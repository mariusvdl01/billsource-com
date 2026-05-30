<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/15/15
 * Time: 3:22 AM
 */

namespace console\controllers;

use common\models\Status;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UpdateStatusController extends Controller
{
    public function getHelp()
    {

        $description = "DESCRIPTION\n";
        $description .= '    '."This command will update the status table. Make sure database migrations are up to date.\n";
        return parent::getHelp() . $description;
    }

    /**
     * Add more bill status to existing status
     */
    public function actionMigrate()
    {
        $this->ensureMigratedDatabase();

        //provide the opportunity for the user to abort the request
        $message = "This command will update the status table.\n";
        $message .= "Would you like to continue?";

        if ($this->confirm($message)) {
            $statuses = Status::find()->all();
            if(!empty($statuses)) {
                foreach ($statuses as $status) {
                    switch($status->name) {
                        case 'Accepted':
                            $status->code = Status::STATUS_ACCEPTED;
                            break;
                        case 'Disputed':
                            $status->code = Status::STATUS_DISPUTED;
                            break;
                        case 'Paid':
                            $status->code = Status::STATUS_PAID;
                            break;
                        case 'Pending':
                            $status->code = Status::STATUS_PENDING;
                            break;
                        case 'Rejected':
                            $status->code = Status::STATUS_REJECTED;
                            break;
                        case 'Sent':
                            $status->code = Status::STATUS_SENT;
                            break;
                        case 'Unpaid':
                            $status->code = Status::STATUS_UNPAID;
                            break;
                        case 'Refund':
                            $status->code = Status::STATUS_REFUND;
                            break;
                    }
                    $status->save();
                }
                $this->stdout("Status table updated successfully\n", Console::FG_GREEN);
            } else {
                $this->stdout("No update was performed on status table\n", Console::FG_YELLOW);
            }
        }
    }

    protected function ensureMigratedDatabase()
    {
        //ensure that column exist
        $table = Yii::$app->db->schema->getTableSchema(Status::tableName());
        if (!isset($table->columns['code'])) {
            $message = "Database migration to update the status table has not been applied.\n";
            $this->stderr($message, Console::FG_RED);
            exit;
        }
    }
}