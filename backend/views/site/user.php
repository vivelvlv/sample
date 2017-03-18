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

$this->title = Yii::t('backend', 'Update User') . ' ' . $model->user_name;
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="user-update">

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
                      'user_name'=>['type'=>Form::INPUT_STATIC],
                  ],
                ],
                [
                  'attributes'=>[       // 1 column layout
                      'work_no'=>['type'=>Form::INPUT_STATIC],
                  ],
                ],
                [
                  'attributes'=>[       // 1 column layout
                      'email'=>['type'=>Form::INPUT_STATIC],
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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' =>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>





