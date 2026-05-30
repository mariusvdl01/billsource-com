<?php

use yii\db\Schema;
use yii\db\Migration;

class m150711_115851_bill_request_init extends Migration
{
    public function safeUp()
    {
    	$this->batchInsert('{{%bill_request}}', ['type', 'description'], [
            ['1', 'Municipal (Water & Light, Rates & Taxes)'],
            ['1', 'Managed Voice and Data Services (Mobile, Broadband, Hosting, etc.)'],
            ['1', 'Managed Voice Only (Standard Fixed Line)'],
            ['1', 'Memberships (Association, Business Network, etc.)'],
            ['1', 'Other Monthly Re-occuring'],
            ['2', 'Foschini'],
            ['2', 'Woolworths'],
            ['2', 'Edgars'],
            ['2', 'Truworths'],
            ['2', 'Builders Warehouse'],
            ['2', 'Mr Price'],
            ['2', 'Identity'],
            ['2', 'Jet'],
            ['2', 'Legit'],
            ['2', 'Markhams'],
            ['2', 'Sportscene'],
            ['2', 'TELKOM'],
            ['2', 'Vodacom'],
            ['2', 'CellC'],
            ['2', 'Neotel'],
            ['2', 'MTN'],
            ['2', 'Traffic Fines'],
            ['2', 'Multichoice'],
            ['2', 'City of Cape Town'],
            ['2', 'City of JHB'],
            ['2', 'City of Tshwane'],
            ['2', 'SABC TV License'],
    	]);
    }

    public function safeDown()
    {
        $this->truncateTable('{{%bill_request}}');
    }
}
