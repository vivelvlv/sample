<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="region-create">
	<div class="region-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name')->textInput() ?>

	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('backend', 'Create') , ['class' =>'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>
