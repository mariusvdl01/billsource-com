<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="site-error">

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
                <?= var_dump($exception) ?>
            </div>

            <p>
                The above error occurred while the Web server was processing your request.
            </p>
            <p>
                Please contact us if you think this is a server error. Thank you.
            </p>

        </div>
    </div>
</div>
