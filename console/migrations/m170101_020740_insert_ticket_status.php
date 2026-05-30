<?php

use console\migrations\BaseMigration;

class m170101_020740_insert_ticket_status extends BaseMigration
{
    public function up()
    {
        $data = [];
        $status = new \common\models\Status();
        $ticketStatuses = $status->getTicketStatuses();
        foreach ($ticketStatuses as $key => $status) {
            $data[] = [$status, $key];
        }
        $this->batchInsert('{{%status}}', ['name', 'code'], $data);
    }

    public function down()
    {
    }
}
