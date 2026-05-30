<?php

namespace api\modules\v1\models;

/**
 * User registration/signup form
 *
 */
class SignupForm extends \common\models\SignupForm
{
    public function loadPost($post)
    {
        if ($post) {
            $this->firstname = $post['firstName'];
            $this->lastname = $post['lastName'];
            $this->email = $post['email'];
            $this->password = $post['password'];
            $this->confirmPassword = $post['repeatPassword'];

            return true;
        }

        return false;
    }
}
