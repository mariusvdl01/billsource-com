<?php

namespace common\models\individual;

use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "individual_financial".
 *
 * @property integer $id
 * @property integer $individual_id
 * @property double $home_1
 * @property double $home_2
 * @property double $home_3
 * @property double $vehicle_1
 * @property double $vehicle_2
 * @property double $craft
 * @property double $insurance
 * @property double $investments
 * @property double $savings
 * @property double $total_assets
 * @property double $bond_1
 * @property double $bond_2
 * @property double $bond_3
 * @property double $car_loan_1
 * @property double $car_loan_2
 * @property double $craft_loan
 * @property double $debt
 * @property double $outstanding_bills
 * @property double $total_liabilities
 * @property double $gross_income
 * @property double $net_income
 * @property double $total_expenses
 * @property double $surplus
 *
 * @property IndividualClient $individual
 */
class IndividualFinancial extends \common\models\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'individual_financial';
    }
    
    /**
     * Behaviors rules to initialize safe variables (such as created_at, updated_at)
     * prior to saving user's data.
     *
     * @return array array of behaviors
     */
    public function behaviors()
    {
    	return [
    		[
    			'class' => TimestampBehavior::className(),
    			'createdAtAttribute' => false,
    			'updatedAtAttribute' => false,
    		]
    	];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['individual_id'], 'required'],
            ['individual_id', 'integer'],
            [['home_1', 'home_2', 'home_3', 'vehicle_1', 'vehicle_2', 
            'craft', 'insurance', 'investments', 'savings', 'total_assets', 
            'bond_1', 'bond_2', 'bond_3', 'car_loan_1', 'car_loan_2', 
            'craft_loan', 'debt', 'outstanding_bills', 'total_liabilities', 
            'gross_income', 'net_income', 'total_expenses', 'surplus'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'home_1' => 'Home 1',
            'home_2' => 'Home 2',
            'home_3' => 'Home 3',
            'vehicle_1' => 'Motor Vehicle 1',
            'vehicle_2' => 'Motor Vehicle 2',
            'craft' => 'Leisure Craft',
            'insurance' => 'Insurance Policies (RA Life)',
            'investments' => 'Portfolio Investments',
            'savings' => 'Savings (Cash, Deposits)',
            'bond_1' => 'Bond 1',
            'bond_2' => 'Bond 2',
            'bond_3' => 'Bond 3',
            'car_loan_1' => 'Vehicle Finance 1',
            'car_loan_2' => 'Vehicle Finance 2',
            'craft_loan' => 'Leisure Craft Finance',
            'debt' => 'Short Term Debt (Personal Loan)',
            'gross_income' => 'Monthly Gross Income',
            'net_income' => 'Monthly Net Income (paid into account)',
            'total_expenses' => 'Total Monthly Expenses',
            'outstanding_bills' => 'Outstanding Bills',
            'total_assets' => 'My Total Assets',
            'total_liabilities' => 'My Total Liabilities',
            'surplus' => 'Surplus Monthly Income',
        ];
    }
    
    /**
     * Retrieves user data
     *
     * @param integer $user_id the id of the current user
     * @return array array containing the result of the query or an empty string if no record is retrived
     */
    public function getFinancialProfileData($user_id) {
    	$query = self::find();
    	$user = User::findOne(['id' => $user_id]);
    	
    	$data = $query->where('[[individual_client.user_id]]=:user_id', [':user_id' => $user->id])
    				->joinWith('individual', true, 'LEFT JOIN')
    				->createCommand()
    				->queryOne();
    	 
    	return $data === false ? [] : $data;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndividual()
    {
        return $this->hasOne(IndividualClient::className(), ['id' => 'individual_id']);
    }
}
