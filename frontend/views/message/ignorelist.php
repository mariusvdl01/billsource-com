<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel thyseus\message\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('message', 'Ignorelist');
$this->params['breadcrumbs'][] = ['label' => Yii::t('message', 'Messages'), 'url' => ['//message/message/inbox']];
$this->params['breadcrumbs'][] = $this->title;

//rmrevin\yii\fontawesome\AssetBundle::register($this);
?>
<div class="panel-body">
    <div class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <h1><?= Html::encode($this->title) ?></h1>

                <?= Html::beginForm(['//message/message/ignorelist'], 'post'); ?>

                <?= Select2::widget([
                    'name' => 'ignored_users',
                    'value' => $ignored_users,
                    'data' => ArrayHelper::map($users, 'id', 'username'),
                    'options' => [
                        'multiple' => true,
                    ],
                    'language' => Yii::$app->language ? Yii::$app->language : null,
                ]); ?>

                <div class="form-group" style="margin-top: 10px;">
                    <?= Html::submitButton(Yii::t('message', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>
                <?= Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>


