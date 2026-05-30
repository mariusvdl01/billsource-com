<?php

/* @var $this yii\web\View */
/* @var $user frontend\models\User */
?>

Dear Counsellor,

The following user requested to be contacted for <?= ($type == 'assistance') ? 'Debt Counselling' : 'a loan' ?>

<?= $user->first_name . ' ' . $user->last_name ?>
Phone number: <?= $user->mobile ?>
<?= $user->address_street ?>
<?= $user->address_region ?>
<?= $user->province->name ?>
<?= $user->address_code ?>  

