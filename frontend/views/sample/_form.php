<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\SampleUnit;
use common\models\SampleType;

/* @var $this yii\web\View */
/* @var $model common\models\Sample */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sample-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'unit')->widget(Select2::classname(), [
//        'language' => Yii::$app->language,
//        'data' => SampleUnit::sampleUnitItems(),
//        'options' => ['placeholder' => Yii::t('frontend', 'Please Select ...')],
//        'pluginOptions' => [
//            'allowClear' => true
//        ],
//    ]); ?>

    <?= $form->field($model, 'document')->widget(FileInput::classname(), [
        'options' => ['multiple' => false],
        'pluginOptions' => ['previewFileType' => 'any']
    ]); ?>


    <?= $form->field($model, 'comment')->textArea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create') : Yii::t('frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
