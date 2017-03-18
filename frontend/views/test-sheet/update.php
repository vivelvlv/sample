<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

use common\models\TestSheet;
/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = Yii::t('frontend', 'Update Test Sheet');

$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Test Sheets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="test-sheet-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

     <?= $form->field($model, 'storage_condition')->radioList(TestSheet::storageConditionItems(),['inline'=>true]) ?>
     <br>
     <?= $form->field($model, 'sample_handle_type')->radioList(TestSheet::sampleHandleTypeItems(),['inline'=>true]) ?>
     <br>

    <?= $form->field($model, 'comment')->textArea(['rows' => 3]) ?>

    <div class="form-group">
    	<?= Html::submitButton(Yii::t('frontend', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
