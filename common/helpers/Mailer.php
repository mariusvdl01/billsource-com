<?php
/**
 * Created by PhpStorm.
 * User: kenny
 * Date: 3/5/17
 * Time: 2:16 AM
 */

namespace common\helpers;

class Mailer extends \yii\swiftmailer\Mailer
{
    /**
     * Sends an email using precompiled templates.
     *
     * @param string $to target email address
     * @param string $subject email subject
     * @param array $template array of templates
     * @param array $params parameters to bind to template during templates composition
     * @param  object $context application context
     *
     * @return boolean whether the model passes validation
     */
    public function sendEmailWithTemplate($to, $subject, $template, $params, $context)
    {
        $message = $this->compose($template, $params);

        return $message->setFrom([$context->params['supportEmail'] => $context->name])
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }

    /**
     * Sends an email without template.
     *
     * @param array $config options array to configure mailer
     *
     * @return boolean
     */
    public function sendEmail($config = [], $context)
    {
        if ($config) {
            $mailer = $this->compose();
            $mailer->setFrom([$context->params['supportEmail'] => $context->name]);

            if (isset($config['email']))
                $mailer->setTo($config['email']);
            else
                return false;

            $mailer->setSubject($config['subject']);

            if (isset($config['body']) && $config['body'])
                $mailer->setTextBody($config['body']);

            return $mailer->send();
        }

        return false;
    }
}