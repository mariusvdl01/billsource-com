<?php
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
/* @var $invoice common\models\invoice\Invoice */

$assetBundle = BillsourceAsset::register($this);

?>
<table>
    <tr>
        <td colspan="4">
            <p>Dear Debtor,</p>
            <p>This is to notify you that Billsource has outstanding bills for you,
                via our bill presentment and payment system&#8722;<br</p>
            <p>
                To prevent aditional action and/or fees. logon to <a
                    href="<?= Yii::$app->params['hostInfo'] ?>"><?= Yii::$app->params['serverName'] ?></a>
                to pay your bills.
            </p>
            <p>Billsource is a Biller Service Provider (BSP) notifying debtors of outstanding accounts,
                currently operating on South Africa under the PASA, CPA and ECT acts protecting individuals and their
                personal information.</p>
            <p>To view this invoice register here or login at <a
                    href="<?= Yii::$app->params['hostInfo'] ?>"><?= Yii::$app->params['serverName'] ?></a>
                &#8722; your first time registration will earn you 150 loyalty points redeemable against our<br>
                catalogue of PC and mobile accessories.</p>
            <p>Billsource also offer you a way to calculate your debt ratio, your net worth, you can securely
                ask for debt counselling or you can request a loan with all the major banks at once to get the best
                interest rate.</p>
            <br>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <p>Thank you for your attention<br>
                BillSource Customer Care<br>
                <a href="mailto://<?= Yii::$app->params['supportEmail'] ?>"><?= Yii::$app->params['supportEmail'] ?></a><br>
                +27 82 867 6875</p>
        </td>
        <td align="right">
            <img src="<?= Yii::$app->params['hostInfo'] ?>/images/logo2.png"
                 alt="Bill Source Logo"/>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <p>Visit <a href="<?= Yii::$app->params['hostInfo'] ?>"><?= Yii::$app->params['serverName'] ?></a>
                regularly to make sure you do not
                have any outstanding bills so that you maintain a healthy credit and payment rating, This<br>
                will ensure you will have access to loan or credit in the long run or whenever you need it.</p>
            <p>Black listed already, click here for an unsecured loan of up to R150,000.00. If you are
                in debt struggling to make payments, click here to<br>
                contact one of our friendly and helpful debt counsellors.</p>
            <p>BillSource is not a Credit Bureau &#8722; we work with registered Credit and Financial providers
                and offer an obligation free service to you, the<br>
                individual, helping you to obtain a stress free financial life! Copyright &copy; 2013 BillSource.co.za
                &#8722;
                all your bills in one place. All rights reserved. To stop<br>
                receiving email notifications, log in and change the e&#8722;mail settings on your personal profile
                or click <a href="<?= Yii::$app->params['hostInfo'] ?>">here</a> to unsubscribe.</p>
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
        <td align="right">
            Like us on <a name="tin3h" href="http://www.facebook.com/billsource">facebook</a>
        </td>
        <td align="right">
            or follow us on <a name="tin3h" href="http://www.twitter.com/@allyourbills">Twitter</a>
        </td>
    </tr>
</table>