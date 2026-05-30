<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
 Regards, The <a href="<?= Yii::$app->params['domain'] ?>">BillSource</a> team.
<?php $this->endPage() ?>
