<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use common\models\Service;


/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = Yii::t('frontend', 'Create Sample Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Sample Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service-create">

	<div class="sample-service-form">

	    <?php $form = ActiveForm::begin(); ?>

	       <?= $form->field($model, 'service_id')->widget(Select2::classname(), [
            'language' => Yii::$app->language,
            'data' => Service::serviceItems(),
            'options' => [
                     'placeholder' => Yii::t('frontend','Please Select ...'),
                      'multiple'=>true],
            'pluginOptions' => [
                'allowClear' => true
            ],
          ]);?>


	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('frontend', 'Create') , ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>
