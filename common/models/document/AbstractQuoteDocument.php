<?php

namespace common\models\document;

abstract class AbstractQuoteDocument extends AbstractDocument
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

	/**
     * Find all invoices belonging to this client
     *
     * @param integer $client_id the client id used for searching the database
     *
     * @return string containing the result of the query or an empty string if no record is retrived
     */
    public static function findQuoteByBusinessId()
    {
        return self::findInvoiceByUserId();
    }

    public static function findBusinessQuotesByCreditor()
    {
        $query = '(SELECT a.id, inv.id AS quote_id, trading_name , CONCAT(\'EM\', reference_number)
				AS reference_number ,discount, paid, comments , due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			INNER JOIN invoice_age_type ON age_paid = paid
    				AND (age_paid = 1 or (age_paid = 0 AND DATEDIFF(NOW(), due_date) >= minimum_days
    				AND DATEDIFF(NOW(), due_date) <= maximum_days))
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a`
    			ON a.invoice_id = inv.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND status_id =:statusId
    			AND client_email IN
    							(SELECT bc.email FROM user u
    							INNER JOIN business_client bc ON u.id = bc.user_id
    							AND business_user = 1
    							AND u.id =:userId)
    			AND inv.business_id <> :businessId)
		
    			UNION
		
    			(SELECT a.id, inv.id AS quote_id, trading_name, CONCAT(\'EM\', reference_number) AS
    				reference_number, discount, paid, comments, due_date, minimum_days, description, image, total
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			INNER JOIN invoice_age_type ON DATEDIFF(NOW(), due_date) >= minimum_days AND DATEDIFF(NOW(), due_date) <= maximum_days
    			LEFT JOIN (SELECT * FROM invoice_payment WHERE payment_result = 0) `a`
    			ON a.invoice_id = inv.id
    			WHERE deleted =deleted
    			AND inv.type =:type
    			AND status_id =:statusId
    			AND client_id IN
    							(SELECT registration_number FROM user u
    							INNER JOIN business_client bc on u.id = bc.user_id
    							AND business_user = 1
    							AND u.id =:userId)
    			AND inv.business_id <> :businessId)';

        return $query;
    }

    public static function findQuotesForIndividualByUserId()
    {
        $query = '(SELECT inv.id, trading_name , reference_number , amount, discount, paid, comments , due_date, total, inv.read
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND status_id =:status
    			AND client_id IN (SELECT id_number FROM user u
    				INNER JOIN individual_client c ON u.id = c.user_id
    				AND business_user = 0
    				AND u.id =:id
                ))
	
    			UNION
    			
				(SELECT inv.id, trading_name , reference_number , amount, discount, paid, comments , due_date, total, inv.read
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND status_id =:status
    			AND client_email IN (SELECT c.email FROM user u
    				INNER JOIN individual_client c ON u.id = c.user_id
    				AND business_user = 0
    		        AND u.id =:id
                ))
	
    			UNION
	
    			(SELECT inv.id, trading_name, reference_number, amount, discount, paid, comments, due_date, total, inv.read
    			FROM ' . parent::tableName() . ' inv
    			INNER JOIN business_client bc ON inv.business_id = bc.id
    			WHERE deleted =:deleted
    			AND inv.type =:type
    			AND status_id =:status
    			AND client_mobile IN (SELECT mobile FROM user u
    				INNER JOIN individual_client c on u.id = c.user_id
    				AND business_user = 0
    				AND u.id =:id
                ))';

        return $query;
    }
}