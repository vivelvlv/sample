<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use kartik\file\FileInput;
use kartik\widgets\Select2;
use kartik\builder\Form;
use kartik\builder\FormGrid;

use common\models\User;


/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = Yii::t('frontend', 'Update User') . ' ' . $model->user_name;
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
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
                  'contentBefore'=>'<legend class="text-info"><small>'.Yii::t('frontend','Basic Information').'</small></legend>',
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


    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Update'), ['class' =>'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>





