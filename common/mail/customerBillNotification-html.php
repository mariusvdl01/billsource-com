<?php
use frontend\assets\BillsourceAsset;

/* @var $this yii\web\View */
/* @var $biller common\models\business\BusinessClient
/* @var $bill common\models\invoice\Invoice */

$assetBundle = BillsourceAsset::register($this);

?>
<div class="invoice-notification">
    <table>
        <tr>
            <td colspan="4">
                <p>Dear <?= $bill->alt_business_name ?>,</p>
                <p>This is to notify you that <?= $biller->trading_name; ?> sent you a(n)
                    <?= $bill->getTypeDescription()[$bill->type] ?> for R<?= $bill->amount ?> (VAT excl.)
                    via our bill presentment and payment system &#8722; where you will find all your bills in one place.
                </p>
                <p>Billsource is a Biller Service Provider (BSP) notifying debtors of outstanding accounts on behalf
                    of <?= $biller->trading_name; ?>,
                    currently operating on South<br> Africa under the PASA, CPA and ECT acts
                    protecting individuals and their personal information.
                </p>
                <p>
                    To view this invoice register here or login at <a
                            href="<?= Yii::$app->params['hostInfo'] ?>"><?= Yii::$app->params['domain'] ?></a>
                    &#8722; your
                    first time registration will earn you 150 loyalty points redeemable
                    against our<br> catalogue of PC and mobile accessories.
                </p>
                <p>
                    Billsource also offer you a way to calculate your debt ratio, yournet worth, you can securely ask
                    debt counselling or you can request a loan with all the major banks at once to get the best interest
                    rate.
                </p> <br>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p>
                    Thank you for your attention<br> BillSource Customer Care<br> <a
                        href="mailto://<?= Yii::$app->params['supportEmail'] ?>"><?= Yii::$app->params['supportEmail'] ?></a><br>
                    +27 82 867 6875
                </p>
            </td>
            <td align="right"><img
                        src="<?= Yii::$app->params['hostInfo'] ?><?= $assetBundle->baseUrl ?>/images/logo.png"
                        alt="Bill Source Logo"/>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <p>
                    Visit <a
                            href="<?= Yii::$app->params['hostInfo'] ?>"><?= Yii::$app->params['domain'] ?></a>
                    regularly to make sure you do not have any outstanding bills so that
                    you maintain a healthy credit and payment rating, This<br> will
                    ensure you will have access to loan or credit in the long run or
                    whenever you need.
                </p>
                <p>
                    Black listed already, click here for an unsecured loan of up to
                    R150,000.00. If you are in debt struggling to make payments, click
                    here to<br> contact one of our friendly and helpful debt
                    counsellors.
                </p>
                <p>
                    BillSource is not a Credit Bureau &#8722; we work with registered
                    Credit and Financial providers and offer an obligation free service
                    to you, the<br> individual, helping you to obtain a stress free
                    financial life! Copyright &copy; 2013 BillSource.com &#8722; all
                    your bills in one place. All rights reserved. To stop<br> receiving
                    email notifications, log in and change the e&#8722;mail settings on
                    your personal profile or click <a
                            href="<?= Yii::$app->params['hostInfo'] ?>">here</a> to unsubscribe.
                </p>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="right">Like us on <a name="tin3h" href="http://www.facebook.com/billsource">facebook</a>
            </td>
            <td align="right">or follow us on <a name="tin3h" href="http://www.twitter.com/@allyourbills">Twitter</a>
            </td>
        </tr>
    </table>
</div>