<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\models\User;

use common\models\TestSheet;

/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = Yii::t('frontend', 'Create Test Sheet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Test Sheets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-sheet-create">

    <legend class="text-info">
        <small> <?= Yii::t('frontend', 'First Step: Add Basic Information') ?></small>
    </legend>

    <?php $form = ActiveForm::begin(); ?>


    <?= Yii::$app->user->identity->is_super ? $form->field($model, 'user_id')->widget(Select2::classname(), [
        'language' => Yii::$app->language,
        'data' => User::userAttributeLabel(),
        'options' => ['placeholder' => Yii::t('frontend', 'Please Select ...')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) : ""; ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'storage_condition')->radioList(TestSheet::storageConditionItems(), ['inline' => true]) ?>
    <!--    <br>-->
    <!--    --><? //= $form->field($model, 'service_type')->radioList(TestSheet::serviceTypeItems(), ['inline' => true]) ?>
    <!--    <br>-->
    <!--    --><? //= $form->field($model, 'report_fetch_type')->radioList(TestSheet::fetchReportTypeItems(), ['inline' => true]) ?>
    <br>
    <?= $form->field($model, 'sample_handle_type')->radioList(TestSheet::sampleHandleTypeItems(), ['inline' => true]) ?>
    <br>

    <?= $form->field($model, 'comment')->textArea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Next Step'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
