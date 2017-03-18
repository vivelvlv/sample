<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="region-update">
	<div class="region-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name')->textInput() ?>

	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('backend', 'Update') , ['class' =>'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>
