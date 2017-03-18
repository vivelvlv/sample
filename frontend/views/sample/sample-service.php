<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Service;


/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = Yii::t('frontend', 'Sample Service');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service">

	<div class="sample-service-form">

	    <?php $form = ActiveForm::begin(); ?>


	       <?= $form->field($model, 'services')->widget(Select2::classname(), [
            'language' => Yii::$app->language,
            'data' => Service::serviceItems(),
            'options' => [
                     'placeholder' => Yii::t('frontend','Please Select ...'),
                      'multiple'=>true],
            'pluginOptions' => [
                'allowClear' => true
            ],
          ]);?>

      <br>
      <br>
	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('frontend', 'Submit') , ['class' => 'btn btn-success']) ?>
	    </div>
      <br>
	    <?php ActiveForm::end(); ?>

	</div>
</div>
