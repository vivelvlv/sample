<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SampleService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sample-service-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'feedback')->textarea(['row' => 4]) ?>

    <?= $form->field($model, 'status')->radioList(\common\models\Complaint::statusItems()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
