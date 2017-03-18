<?php

use common\models\ServiceType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\widgets\Select2;

use common\models\Device;

/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'catalog_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 3]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->widget(Select2::classname(), [
        'language' => Yii::$app->language,
        'data' => ServiceType::typeAttributeLabel(),
        'options' => ['placeholder' => Yii::t('backend', 'Please Select ...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'device_id')->widget(Select2::classname(), [
        'language' => Yii::$app->language,
        'data' => Device::deviceAttributeLabel(),
        'options' => ['placeholder' => Yii::t('backend', 'Please Select ...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'comment')->textArea(['rows' => 3]) ?>

    <?= $form->field($model, 'is_show')->widget(CheckboxX::classname(), [
        'initInputType' => CheckboxX::INPUT_CHECKBOX,
        'pluginOptions' => [
            'theme' => 'krajee-flatblue',
            'enclosedLabel' => true,
            'threeState' => false
        ]
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
