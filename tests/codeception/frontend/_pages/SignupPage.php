<?php

namespace tests\codeception\frontend\_pages;

use \yii\codeception\BasePage;

/**
 * Represents signup page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class SignupPage extends BasePage
{

    public $route = 'account/signup';

    /**
     * @param array $signupData
     */
    public function submit(array $signupData)
    {
        foreach ($signupData as $field => $value) {
            $inputType = $field === 'body' ? 'textarea' : 'input';
            if($field=='category')
                $this->actor->selectOption(['name'=>"SignupForm[category]"], $value);
            elseif($field=='tcs')
                $this->actor->checkOption(['name'=>"SignupForm[tcs]"], $value);
            else
                $this->actor->fillField($inputType . '[name="SignupForm[' . $field . ']"]', $value);
        }
        $this->actor->click('Submit');
    }
}
