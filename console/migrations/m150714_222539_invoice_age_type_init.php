<?php

use yii\db\Schema;
use yii\db\Migration;

class m150714_222539_invoice_age_type_init extends Migration
{
    public function safeUp()
    {
    	$this->batchInsert('{{%invoice_age_type}}',
    		['minimum_days', 'maximum_days', 'description', 'image', 'invoice_fee',
    		'business_fee', 'invoice_reference', 'debtor_description', 'creditor_description',
    		'invoice_description', 'age_paid', 'allow_payment'],
    		[
    			['-9000', '30', 'Current', 'dbtor_icon_blue.png', '0', '0', 'LETTER FEE', 'Current', 'Current', 'Current', '0', '1'],
    			['31', '60', '30 Days Pre Red letter', 'dbtor_icon_orange.png', '9.95', '99.95', 'ORANGE LETTER FEE', '30 Days Pre red letter (R9.95 excl. VAT fee per bill)',
    			'30 Days (R99.95 excl. VAT fee per bill)', '30 Days Pre Red letter', '0', '1'],
    			['61', '90', '60 Days Red letter', 'dbtor_icon_red.png', '19.95', '159.95', 'RED LETTER FEE', '60 Days Red letter (R19.95 excl. VAT fee per bill)', 
    			'60 Days (R159.95 excl. VAT fee per bill)', '60 Days Red leeter', '0', '1'],
    			['91', '120', '90 Days Pre-handed over', 'dbtor_icon_grey.png', '29.95', '199.95', 'BLACK LETTER FEE', '90 Days Pre-handed over (R29.95 excl. VAT fee per bill)',
    			'90 Days (R199.95 excl. VAT fee per bill)', '90 Days Pre-handed over', '0', '1'],
    			['121', '9000', '120+ Handed over', 'dbtor_icon_black.png', '0', '0', 'LETTER FEE', '120+ Handed over', '120+ Handed over', '120+ Handed over', '0', '0'],
    			['0', '0', 'Paid', 'dbtor_icon_green.png', '0', '0', 'FEE', 'Paid', 'Paid', 'Paid', '1', '1'],
    	]);
    }

    public function safeDown()
    {
        $this->truncateTable('{{%invoice_age_type}}');
    }
}
