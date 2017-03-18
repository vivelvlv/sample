<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\builder\Form;
use kartik\builder\FormGrid;
use common\models\RegionProvince;
use common\models\RegionCity;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('backend', 'Update User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="user-update">

	<div class="user-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation'=>false]); ?>

	    <?php
	        echo FormGrid::widget([
	            'model'=>$model,
	            'form'=>$form,
	            'autoGenerateColumns'=>true,
	            'rows'=>[
	                [
	                  'contentBefore'=>'<legend class="text-info"><small>'.Yii::t('backend','Basic Information').'</small></legend>',
	                  'attributes'=>[       // 1 column layout
	                      'user_name'=>['type'=>Form::INPUT_TEXT],
	                  ],
	                ],
	                [
	                  'attributes'=>[       // 2 column layout
	                      'email'=>['type'=>Form::INPUT_TEXT],
	                      'mobile_phone'=>['type'=>Form::INPUT_TEXT],
	                  ],
	                ],

	                [
	                 'attributes'=>[       // 2 column layout
	                    'company_name'=>['type'=>Form::INPUT_TEXT],
	                    'company_tax'=>['type'=>Form::INPUT_TEXT]
	                   ]
	                ],
	               [
	                 'attributes'=>[       // 2 column layout
	                    'inputPassword'=>['type'=>Form::INPUT_TEXT],
	                    'reInputPassword'=>['type'=>Form::INPUT_TEXT]
	                   ]
	                ],
	            ]
	        ]);
	    ?>

        <legend class="text-info"><small> <?= Yii::t('backend','Address') ?></small></legend>
	    <?= $form->field($model, 'province',[
	                    'options'=>[
	                      'tag'=>'div',
	                      'class'=>'form-group field-Signup-province has-feedback required'
	                    ],
	                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
	                ])->dropDownList(
	                      ArrayHelper::map(RegionProvince::find()->all(),'province_id','province_name'),
	                      [
	                        'prompt'=>Yii::t('backend', 'Select Province'),
	                        'onchange'=>'
	                                 $.post("index.php?r=region/city&id='.'"+$(this).val(),
	                                  function(data){
	                                      
	                                      $("select#signupform-city").html(data);
	                                  }
	                                  );'
	                      ]) ?>


	    <?= $form->field($model, 'city',[
	                    'options'=>[
	                      'tag'=>'div',
	                      'class'=>'form-group field-Signup-city has-feedback required'
	                    ],
	                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
	                ])->dropDownList(
	                    ArrayHelper::map(RegionCity::find()->all(),'city_id','city_name'),
	                    [
	                      'prompt'=>Yii::t('backend', 'Select City'),
	                    ]) ?>


	    <?= $form->field($model, 'detail_address')->textArea(['rows'=>3,'placeholder'=>Yii::t('backend','Detail Address')]) ?>

	    <div class="form-group">
	        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' => 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
