<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SampleServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sample-service-search">

    <?php $form = ActiveForm::begin([
        'action' => ['deliver'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'deliver_barcodes')->textarea(['rows' => 20, 'width' => 'resize:none'])->hint("请用扫描枪录入条形码") ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Submit'), ['class' => 'btn btn-primary']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
