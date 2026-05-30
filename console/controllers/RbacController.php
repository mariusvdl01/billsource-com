<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class RbacController extends Controller
{
	private $_authManager;
	
	
	public function getHelp()
	{
	
		$description = "DESCRIPTION\n";
		$description .= '    '."This command generates an initial RBAC authorization hierarchy.\n";
		return parent::getHelp() . $description;
	}

    /**
     * initialize RBAC tables with predefined roles
     */
    public function actionInit()
    {
    	$this->ensureAuthManagerDefined();
        $auth = $this->_authManager;

        //provide the oportunity for the user to abort the request
        $message = "This command will create six roles: Super user, Individual, Multi User Admin, Single User Admin, Loader and Reader\n";
        $message .= " and all the underlying permissions:\n";
        $message .= "Would you like to continue?";

        if($this->confirm($message))
        {
        	/* Reader's role */

            // add "viewQuote" permission
	        $viewQuote = $auth->createPermission('viewQuote');
	        $viewQuote->description = 'View quote';
	        $auth->add($viewQuote);

            // add "acceptQuote" permission
	        $acceptQuote = $auth->createPermission('acceptQuote');
	        $acceptQuote->description = 'Accept quote';
	        $auth->add($acceptQuote);

            // add "rejectQuote" permission
	        $rejectQuote = $auth->createPermission('rejectQuote');
	        $rejectQuote->description = 'Reject quote';
	        $auth->add($rejectQuote);

            // add "viewInvoice" permission
	        $viewInvoice = $auth->createPermission('viewInvoice');
	        $viewInvoice->description = 'View invoice';
	        $auth->add($viewInvoice);

            // add "payInvoice" permission
	        $payInvoice = $auth->createPermission('payInvoice');
	        $payInvoice->description = 'Pay invoice';
	        $auth->add($payInvoice);

            // add "vettBusiness" permission
	        $vettBusiness = $auth->createPermission('vettBusiness');
	        $vettBusiness->description = 'Vett business';
	        $auth->add($vettBusiness);

            // add "contactUs" permission
	        $contactUs = $auth->createPermission('contactUs');
	        $contactUs->description = 'Contact us for support';
	        $auth->add($contactUs);

            // add "reader" role and give this role the above permissions
	        $reader = $auth->createRole('reader');
	        $reader->description = 'Business Reader role';
	        $auth->add($reader);
	        $auth->addChild($reader, $viewQuote);
	        $auth->addChild($reader, $acceptQuote);
	        $auth->addChild($reader, $rejectQuote);
	        $auth->addChild($reader, $viewInvoice);
	        $auth->addChild($reader, $payInvoice);
	        $auth->addChild($reader, $vettBusiness);
	        $auth->addChild($reader, $contactUs);

            /* Loader's role */

            // add "viewProfile" permission
	        $viewProfile = $auth->createPermission('viewProfile');
	        $viewProfile->description = 'View profile';
	        $auth->add($viewProfile);

            // add the rule
			$rule = new \common\rbac\UserProfileRule;
			$auth->add($rule);

            // add the "viewOwnProfile" permission and associate the rule with it.
			$viewOwnProfile = $auth->createPermission('viewOwnProfile');
			$viewOwnProfile->description = 'View own profile';
			$viewOwnProfile->ruleName = $rule->name;
			$auth->add($viewOwnProfile);

            // "viewOwnProfile" will be used from "viewProfile"
			$auth->addChild($viewOwnProfile, $viewProfile);

            // add "updateProfile" permission
	        $updateProfile = $auth->createPermission('updateProfile');
	        $updateProfile->description = 'Update profile';
	        $auth->add($updateProfile);

            // add the "updateOwnProfile" permission and associate the rule with it.
	        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
	        $updateOwnProfile->description = 'Update own profile';
	        $updateOwnProfile->ruleName = $rule->name;
	        $auth->add($updateOwnProfile);

            // "updateOwnProfile" will be used from "updateProfile"
	        $auth->addChild($updateOwnProfile, $updateProfile);

            // add "viewCrm" permission
	        $viewCrm = $auth->createPermission('viewCrm');
	        $viewCrm->description = 'View customers';
	        $auth->add($viewCrm);

            // add "createCrm" permission
	        $createCrm = $auth->createPermission('createCrm');
	        $createCrm->description = 'Create customer';
	        $auth->add($createCrm);

            // add "updateCrm" permission
	        $updateCrm = $auth->createPermission('updateCrm');
	        $updateCrm->description = 'Update customer';
	        $auth->add($updateCrm);

            // add "deleteCrm" permission
	        $deleteCrm = $auth->createPermission('deleteCrm');
	        $deleteCrm->description = 'Delete customer';
	        $auth->add($deleteCrm);

            // add "createQuote" permission
	        $createQuote = $auth->createPermission('createQuote');
	        $createQuote->description = 'Create quote';
	        $auth->add($createQuote);

            // add "updateQuote" permission
	        $updateQuote = $auth->createPermission('updateQuote');
	        $updateQuote->description = 'Update quote';
	        $auth->add($updateQuote);

            // add "deleteQuote" permission
	        $deleteQuote = $auth->createPermission('deleteQuote');
	        $deleteQuote->description = 'Delete quote';
	        $auth->add($deleteQuote);

            // add "createInvoice" permission
	        $createInvoice = $auth->createPermission('createInvoice');
	        $createInvoice->description = 'Create invoice';
	        $auth->add($createInvoice);

            // add "updateInvoice" permission
	        $updateInvoice = $auth->createPermission('updateInvoice');
	        $updateInvoice->description = 'Update invoice';
	        $auth->add($updateInvoice);

            // add "deleteInvoice" permission
	        $deleteInvoice = $auth->createPermission('deleteInvoice');
	        $deleteInvoice->description = 'Delete invoice';
	        $auth->add($deleteInvoice);

            // add "archiveInvoice" permission
	        $archiveInvoice = $auth->createPermission('archiveInvoice');
	        $archiveInvoice->description = 'Archive an invoice';
	        $auth->add($archiveInvoice);

            // add "loader" role and give this role the above permissions
	        $loader = $auth->createRole('loader');
	        $loader->description = 'Business Loader role';
	        $auth->add($loader);
	        $auth->addChild($loader, $updateOwnProfile);
	        $auth->addChild($loader, $viewOwnProfile);
	        $auth->addChild($loader, $viewCrm);
	        $auth->addChild($loader, $createCrm);
	        $auth->addChild($loader, $updateCrm);
	        $auth->addChild($loader, $deleteCrm);
	        $auth->addChild($loader, $createQuote);
	        $auth->addChild($loader, $updateQuote);
	        $auth->addChild($loader, $deleteQuote);
	        $auth->addChild($loader, $createInvoice);
	        $auth->addChild($loader, $updateInvoice);
	        $auth->addChild($loader, $deleteInvoice);
	        $auth->addChild($loader, $archiveInvoice);
	        $auth->addChild($loader, $reader);

            /* Single Admin role */

            // add "socialNetwork" permission
	        $socialNetwork = $auth->createPermission('socialNetwork');
	        $socialNetwork->description = 'Integrate with social networks';
	        $auth->add($socialNetwork);

            // add "createReport" permission
	        $createReport = $auth->createPermission('createReport');
	        $createReport->description = 'Create reports';
	        $auth->add($createReport);

            // add "reconciliation" permission
	        $reconciliation = $auth->createPermission('reconciliation');
	        $reconciliation->description = 'Bills reconciliation';
	        $auth->add($reconciliation);

            // add "payBills" permission
	        $payBills = $auth->createPermission('payBills');
	        $payBills->description = 'Pay bills';
	        $auth->add($payBills);

            // add "loadVault" permission
	        $loadVault = $auth->createPermission('loadVault');
	        $loadVault->description = 'Vault invoices';
	        $auth->add($loadVault);

            // add "createUser" permission
	        $createUser = $auth->createPermission('createUser');
	        $createUser->description = 'Create user';
	        $auth->add($createUser);

            // add "updateUser" permission
	        $updateUser = $auth->createPermission('updateUser');
	        $updateUser->description = 'Update user';
	        $auth->add($updateUser);

            // add "createCreditPolicy" permission
	        $createCreditPolicy = $auth->createPermission('createCreditPolicy');
	        $createCreditPolicy->description = 'Create credit policy';
	        $auth->add($createCreditPolicy);

            // add "updateCreditPolicy" permission
	        $updateCreditPolicy = $auth->createPermission('updateCreditPolicy');
	        $updateCreditPolicy->description = 'Update credit policy';
	        $auth->add($updateCreditPolicy);

            // add "singleUserAdmin" role and give this role the above permissions
	        $singleUserAdmin = $auth->createRole('singleUserAdmin');
	        $singleUserAdmin->description = 'Single user administrator role';
	        $auth->add($singleUserAdmin);
	        $auth->addChild($singleUserAdmin, $socialNetwork);
	        $auth->addChild($singleUserAdmin, $createReport);
	        $auth->addChild($singleUserAdmin, $reconciliation);
	        $auth->addChild($singleUserAdmin, $payBills);
	        $auth->addChild($singleUserAdmin, $loadVault);
	        $auth->addChild($singleUserAdmin, $createUser);
	        $auth->addChild($singleUserAdmin, $updateUser);
	        $auth->addChild($singleUserAdmin, $createCreditPolicy);
	        $auth->addChild($singleUserAdmin, $updateCreditPolicy);
	        $auth->addChild($singleUserAdmin, $loader);

            /* Business administrator role */

            // add "adminCompany" permission
	        $adminCompany = $auth->createPermission('adminCompany');
	        $adminCompany->description = 'Administer companies';
	        $auth->add($adminCompany);

            // add "deleteProfile" permission
	        $deleteProfile = $auth->createPermission('deleteProfile');
	        $deleteProfile->description = 'Delete profile';
	        $auth->add($deleteProfile);

            // add "createProfile" permission
	        $createProfile = $auth->createPermission('createProfile');
	        $createProfile->description = 'Create profile';
	        $auth->add($createProfile);

            // add "businessAdmin" role and give this role the above permissions
	        $businessAdmin = $auth->createRole('businessAdmin');
	        $businessAdmin->description = 'Business administrator';
	        $auth->add($businessAdmin);
	        $auth->addChild($businessAdmin, $createProfile);
	        $auth->addChild($businessAdmin, $deleteProfile);
	        $auth->addChild($businessAdmin, $adminCompany);
	        $auth->addChild($businessAdmin, $singleUserAdmin);

	        // add "viewFinancial" permission
	        $viewFinancial = $auth->createPermission('viewFinancial');
	        $viewFinancial->description = 'View financial';
	        $auth->add($viewFinancial);

            // add "updateFinancial" permission
	        $updateFinancial = $auth->createPermission('updateFinancial');
	        $updateFinancial->description = 'Update financial';
	        $auth->add($updateFinancial);

            // add "requestAssistance" permission
	        $requestAssistance = $auth->createPermission('requestAssistance');
	        $requestAssistance->description = 'Request assistance';
	        $auth->add($requestAssistance);

            // add "createReadings" permission
	        $createReadings = $auth->createPermission('createReadings');
	        $createReadings->description = 'Create readings';
	        $auth->add($createReadings);

            // add "updateReadings" permission
	        $updateReadings = $auth->createPermission('updateReadings');
	        $updateReadings->description = 'Update readings';
	        $auth->add($updateReadings);

            /* Individual role */
	        $individual = $auth->createRole('individual');
	        $individual->description = 'Individual user\'s role';
	        $auth->add($individual);
	        $auth->addChild($individual, $viewOwnProfile);
	        $auth->addChild($individual, $updateOwnProfile);
	        $auth->addChild($individual, $viewFinancial);
	        $auth->addChild($individual, $updateFinancial);
	        $auth->addChild($individual, $loadVault);
	        $auth->addChild($individual, $viewInvoice);
	        $auth->addChild($individual, $payInvoice);
	        $auth->addChild($individual, $contactUs);
	        $auth->addChild($individual, $socialNetwork);
	        $auth->addChild($individual, $viewQuote);
	        $auth->addChild($individual, $acceptQuote);
	        $auth->addChild($individual, $rejectQuote);
	        $auth->addChild($individual, $requestAssistance);
	        $auth->addChild($individual, $createReadings);
	        $auth->addChild($individual, $updateReadings);

            /* Super user role */

            // add "sysAdmin" permission
	        $sysAdmin = $auth->createPermission('sysAdmin');
	        $sysAdmin->description = 'Administer billsource system';
	        $auth->add($sysAdmin);

            // add "sysAdmin" role and give this role the above permissions
	        $superUser = $auth->createRole('superUser');
	        $superUser->description = 'Super user role';
	        $auth->add($superUser);
	        $auth->addChild($superUser, $businessAdmin);
	        $auth->addChild($superUser, $sysAdmin);
	        $auth->addChild($superUser, $individual);

            $this->stdout("RBAC authorization heirarchy created successfully\n", Console::FG_GREEN);
        }
    }

    protected function ensureAuthManagerDefined()
    {
        //ensure that an authManager is defined as this is mandatory for creating an auth heirarchy
        if (($this->_authManager = Yii::$app->authManager) === null) {
            $message = "Error: an authorization manager, named 'authManager' must be con-figured to use this command.\n";
            $this->stderr($message, Console::FG_RED, Console::UNDERLINE);
            exit;
        }
    }
}