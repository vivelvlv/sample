<?php

use common\models\SampleUnit;
use common\models\TestSheet;
use common\models\User;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\detail\DetailView;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */
/* @var $form yii\widgets\ActiveForm */
/* @var $userInfo */
?>

<?php $attributes = [

    [
        'columns' => [
            [
                'attribute' => 'company_name',
                'displayOnly' => true,
                'name' => "company_info_id",
                'label' => Yii::t('frontend', 'Proxy Name'),
                'valueColOptions' => ['style' => 'width:30%']
            ],
            [
                'attribute' => 'user_name',
                'valueColOptions' => ['style' => 'width:30%'],
                'displayOnly' => true
            ],
        ],
    ],

    [
        'columns' => [
            [
                'attribute' => 'email',
                'format' => 'email',
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%']
            ],
            [
                'attribute' => 'mobile_phone',
                'valueColOptions' => ['style' => 'width:30%'],
                'displayOnly' => true
            ],
        ],
    ],

    [
        'label' => Yii::t('frontend', 'Address'),
        'value' => $userInfo->getAddress(),
    ]

];
//echo DetailView::widget([
//    'model' => $userInfo,
//    'attributes' => $attributes,
//    'bordered' => true,
//    'striped' => true,
//    'condensed' => false,
//    'responsive' => true,
//    'hover' => true,
//    'hAlign' => 'left',
//    'vAlign' => 'middle',
//    'mode' => DetailView::MODE_VIEW,
//
//]);
?>

<?php
echo $this->render("/test-sheet/choose_services_modal_view");
echo $this->render("/test-sheet/choose_unit_modal_view");
echo $this->render("/test-sheet/choose_type_modal_view");
?>

<div class="test-sheet-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= Yii::$app->user->identity->is_super ? $form->field($model, 'user_id')->widget(Select2::classname(), [
        'language' => Yii::$app->language,
        'data' => User::userAttributeLabel(),
        'options' => ['placeholder' => Yii::t('frontend', 'Please Select ...')],
        'pluginOptions' => [
        ],
    ])->label(Yii::t('frontend', 'Select Custom')) : ""; ?>

    <br>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true])->label("测试单名称") ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'storage_condition')->radioList(TestSheet::storageConditionItems(), ['inline' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'sample_handle_type')->radioList(TestSheet::sampleHandleTypeItems(), ['inline' => true]) ?>
        </div>
    </div>


    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 8,
        'min' => 1,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $modelSamples[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'description',
        ],
    ]); ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="text-center">
                <legend class="text-info"><?= Yii::t('frontend', "Add Samples Max 8 Items"); ?></legend>
            </th>
            <th class="text-center">
                <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($modelSamples as $indexSample => $modelSample): ?>
            <tr class="house-item">
                <td>
                    <table class="table">
                        <tr>
                            <td width="25%">
                                <?= $form->field($modelSample, "[{$indexSample}]name")->textInput(['maxlength' => true])->label(Yii::t('frontend', 'sample & serial_number')) ?>
                            </td>
                            <td width="20%">
                                <?= $form->field($modelSample, "[{$indexSample}]project_sn")->textInput(['maxlength' => true]) ?>
                            </td>
                            <td width="20%">
                                <?= $form->field($modelSample, "[{$indexSample}]weight")->textInput(['maxlength' => true]) ?>
                            </td>
                            <td width="10%">
                                <?= $form->field($modelSample, "[{$indexSample}]type_hidden")->textInput(['maxlength' => true, 'class' => 'form-control sample_type']) ?>
                                <?= $form->field($modelSample, "[{$indexSample}]type")->label(false)->hiddenInput() ?>
                            </td>
                            <td>
                                <?= $form->field($modelSample, "[{$indexSample}]document")->widget(FileInput::classname(), [
                                    'options' => ['multiple' => false],
                                    'pluginOptions' => [
                                        //'showCaption'=>false,
                                        'showPreview' => false,
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'initialCaption' => $modelSample->getDocumentTitle()

                                        // 'previewFileType' => 'any',
                                        // 'initialPreview'=> isset($modelSample->document)&& !empty($modelSample->document)?
                                        //    [ Html::img(Yii::$app->upload->imageRootUrl . 'dist/img/file.png',['class'=>'file-preview-image','alt'=>'','title'=>'']),]
                                        //    :[]
                                    ]
                                ])->label(Yii::t('frontend', "Function Doc")); ?>
                            </td>

                        </tr>

                        <tr>
                            <td colspan="2">
                                <?= $form->field($modelSample, "[{$indexSample}]sample_services")->textArea(['rows' => 2, 'class' => 'form-control sample_services_class']) ?>
                                <?= $form->field($modelSample, "[{$indexSample}]sample_services_hidden")->label(false)->hiddenInput() ?>
                            </td>

                            <td colspan="3">
                                <?= $form->field($modelSample, "[{$indexSample}]comment")->textArea(['rows' => 2]) ?>
                            </td>
                        </tr>
                    </table>

                </td>

                <td class="vcenter" hidden>
                    <?php
                    // necessary for update action.
                    if (!$modelSample->isNewRecord) {
                        echo Html::activeHiddenInput($modelSample, "[{$indexSample}]id");
                    }
                    ?>
                </td>
                <td class="text-center center">
                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>



    <?= $form->field($model, 'comment')->textArea(['rows' => 2])->label(Yii::t("frontend", "Test Sheet Comment")) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create') : Yii::t('frontend', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlChangeUser = Url::to(['change-user']);


$script = <<< JS

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    $('textarea.sample_services_class').on('click',function(event){
        var target = event.target;
        var id = $(event.target).attr("id");
        show(event.target,$('#' + id + "_hidden"));
    });
    $('input.sample_unit').on('click',function(event){
        var target = event.target;
        var id = $(event.target).attr("id");
        showUnit(event.target,$('#' + id.substr(0,id.length-7)));
    });
    
    $('input.sample_type').on('click',function(event) {
       var target = event.target;
       var id = $(event.target).attr("id");
       showType(event.target,$('#'+id.substr(0,id.length-7)));
    })

});

$(function(){

    $('textarea.sample_services_class').on('click',function(event){
        var target = event.target;
        var id = $(event.target).attr("id");
        show(event.target,$('#' + id + "_hidden"));
    });

    $('input.sample_unit').on('click',function(event){
        var target = event.target;
        var id = $(event.target).attr("id");
        showUnit(event.target,$('#' + id.substr(0,id.length-7)));
    });
    $('input.sample_type').on('click',function(event) {
      var target = event.target;
      var id = $(event.target).attr("id");
      showType(event.target,$('#'+id.substr(0,id.length-7)));
    })
});
function changeUser(object) {
  $.post( "{$urlChangeUser}", 
              {
                 adu : $(object).val()
              },
              function(){
                $.pjax.reload({container:'#deliver_sample_grid'});
              }
            );
}


JS;
$this->registerJs($script);
?>
