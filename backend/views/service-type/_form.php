<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model backend\models\SampleType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sample-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 3]) ?>

    <?= $form->field($model, 'is_show')->widget(CheckboxX::classname(),[
        'initInputType'=>CheckboxX::INPUT_CHECKBOX,
        'pluginOptions'=>[
            'theme'=>'krajee-flatblue',
            'enclosedLabel'=>true,
            'threeState'=>false
        ]
    ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
