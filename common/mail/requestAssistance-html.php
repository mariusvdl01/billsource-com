<?php

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
?>
<div class="request-assistance">
    <p>Dear Counsellor,</p>

    <p>
        The following user requested to be contacted for <?= ($type == 'assistance') ? 'debt counselling' : 'a loan' ?>
        <br/>

        <?= $user->first_name . ' ' . $user->last_name ?> <br/>
        Phone number: <?= $user->mobile ?> <br/>
        <?= $user->address_street ?> <br/>
        <?= $user->address_region ?> <br/>
        <?= $user->province->name ?> <br/>
        <?= $user->address_code ?> <br/>
    </p>
</div>