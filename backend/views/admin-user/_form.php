<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use kartik\file\FileInput;
use kartik\widgets\Select2;
use kartik\builder\Form;
use kartik\builder\FormGrid;

use common\models\AdminUser;


/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation'=>false,'options' => ['enctype' => 'multipart/form-data']]); ?>

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
                  'attributes'=>[       // 1 column layout
                      'work_no'=>['type'=>Form::INPUT_TEXT],
                  ],
                ],
                [
                  'attributes'=>[       // 1 column layout
                      'email'=>['type'=>Form::INPUT_TEXT],
                  ],
                ],
                [
                 'attributes'=>[       // 2 column layout
                    'mobile_phone'=>['type'=>Form::INPUT_TEXT],
                    'office_phone'=>['type'=>Form::INPUT_TEXT]
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

    <?= $form->field($model, 'image')->widget( FileInput::classname(), [
        'pluginOptions'=>
        [
          'showCaption'=>false,
          'showRemove'=>false,
          'showUpload'=>false,
          'allowedFileExtensions'=>['jpg','png','gif'],
          'initialPreview'=> !$model->image ?[]:
                [ Html::img(Yii::$app->upload->imageRootUrl.$model->image,['class'=>'file-preview-image','alt'=>'','title'=>'']),],
        ],
         'options' => ['accept' => 'image/*'],
     ] ) ?>


    <?= $form->field($model, 'leader_id')->widget(Select2::classname(), [
            'language' => Yii::$app->language,
            'data' => AdminUser::userAttributeLabel(),
            'options' => ['placeholder' => Yii::t('backend','Please Select ...')],
            'pluginOptions' => [
                'allowClear' => true
            ],
    ]);?>



    <?php
        echo FormGrid::widget([
            'model'=>$model,
            'form'=>$form,
            'autoGenerateColumns'=>true,
            'rows'=>[
                [
                'contentBefore'=>'<legend class="text-info"><small>'.Yii::t('backend','Work Area').'</small></legend>',
                 'attributes'=>[       // 2 column layout
                    'lab_building'=>['type'=>Form::INPUT_TEXT],
                    'lab_floor'=>['type'=>Form::INPUT_TEXT],
                    'lab_room'=>['type'=>Form::INPUT_TEXT],
                   ]
                ],
               [
                 'attributes'=>[       // 2 column layout
                    'office_building'=>['type'=>Form::INPUT_TEXT],
                    'office_floor'=>['type'=>Form::INPUT_TEXT],
                    'office_room'=>['type'=>Form::INPUT_TEXT]
                   ]
                ],
            ]
        ]);
    ?>
  
    <legend class="text-info"><small> <?= Yii::t('backend','Other Information') ?></small></legend>

     <?= $form->field($model, 'entry_date')->widget(
       DatePicker::className(), [
        'language' => Yii::$app->language,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
      ]
    ) ?>

   <?= $form->field($model, 'status')->dropDownList(AdminUser::getStatusList()) ?>

   <?= $form->field($model, 'leave_date')->widget(
       DatePicker::className(), [
        'language' => Yii::$app->language,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
      ]
    ) ?>

    <?= $form->field($model,'role')->dropdownList(ArrayHelper::getColumn(Yii::$app->authManager->getRoles(),'name'),
                                                   ['prompt'=>Yii::t('backend', 'Select Role')]
           ) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
$(function () {

   if($('#adminuser-status').val() == '0')
   {
      $('.field-adminuser-leave_date').css('display','block');
   }
   else
   {
      $('.field-adminuser-leave_date').css('display','none');
   }

    $('#adminuser-status').change(function()
    {
         if($('#adminuser-status').val() == '0')
         {
            $('.field-adminuser-leave_date').css('display','block');
         }
         else
         {
            $('.field-adminuser-leave_date').css('display','none');
         }
    });


});

JS;
$this->registerJs($script);
?>
