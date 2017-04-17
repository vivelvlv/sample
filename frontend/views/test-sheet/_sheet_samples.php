<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\models\Sample;
use common\models\SampleType;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="service-index">

    <br>

    <p>

        <?= Html::button(Yii::t('frontend', 'Create Sample'), ['value' => Url::to(['sample/create', 'sheet_id' => $sheet_id, 'is_new' => $is_new]),
            'class' => 'btn btn-success',
            'id' => 'createModalButton'])
        ?>
    </p>

    <?php
    //create
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
        'id' => 'createSampleModal',
        'size' => 'modal-md',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo "<div id='modalConent'></div>";
    Modal::end();

    //create sample service
    Modal::begin([
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
        'header' => '<h4>' . Yii::t('frontend', 'Sample Service') . ' </h4>',
        'id' => 'createModel',
        'size' => 'modal-md',
    ]);
    echo "<div id='createModalConent'></div>";
    Modal::end();


    //update
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
        'id' => 'updateModal',
        'size' => 'modal-md',
    ]);
    echo "<div id='updateModalConent'></div>";
    Modal::end();

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
        'id' => 'viewModal',
        'size' => 'modal-md',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {

                if (!empty($model->sampleServices)) {
                    return GridView::ROW_COLLAPSED;
                } else {
                    return '';
                }
            },
            'detailUrl' => Url::to(['sample-service/index']),
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true,
        ],
        'name',
        'serial_number',
        [
            'attribute' => 'weight',
            'value' => function ($model, $key, $index, $column) {
                return $model->weight;
            },
            'hAlign' => 'left',
            'vAlign' => 'middle',
        ],
        'comment',

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{create-sample-service}',
            'buttons' => [
                'create-sample-service' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('frontend', 'Sample Service'),
                        'aria-label' => Yii::t('frontend', 'Sample Service'),
                        'data-pjax' => '0',
                        'name' => 'modalSampleServiceProject'
                    ];
                    $url = ['sample/sample-service', 'id' => $model->id];
                    return Html::a('<span class="glyphicon glyphicon-align-justify"></span>', $url, $options);
                }
            ],
            'vAlign' => 'middle',
            'width' => '140px',
            'order' => DynaGrid::ORDER_FIX_RIGHT
        ],
    ];


    echo DynaGrid::widget([
        'columns' => $gridColumns,
        'theme' => 'panel-info',
        'showPersonalize' => true,
        'storage' => 'cookie',
        //'allowPageSetting'=>false,
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'bordered' => true,
            'striped' => true,
            'hover' => true,
            'pjax' => true,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('frontend', 'Sample') . '</h3>',
                'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('frontend', 'Show Samples') . '</em></div>',

            ],
            'toolbar' => [
                ['content' => '{dynagrid}'],
            ],

        ],
        'options' => ['id' => 'sheet_samples_grid'] // a unique identifier is important
    ]);
    ?>
    <?= Html::a(Yii::t('frontend', 'Submit'), ['new-test-sheet/submit', 'id' => $sheet_id], ['class' => 'btn btn-primary']) ?>

</div>
<?php
$script = <<< JS
var createModel =  $('#createModel');
$('#sheet_samples_grid-pjax').on('click','a[name="modalSampleServiceProject"]',function(e){
       var url = $(this).attr('href');

        createModel.kbModalAjax({
            url: url,
            ajaxSubmit: 'true',
        });
       
        createModel.modal('show')
                   .find('.modal-body')
                   .load(url); 

        return false;
});

$('#createModel').on('kbModalSubmit', function(event, data, status, xhr) 
{
    createModel.modal('toggle')
    $.pjax.reload({container:'#sheet_samples_grid-pjax'});
});

$('#sheet_samples_grid-pjax').on('click','a[name="modalUpdate"]',function(e){
        $('#updateModal').modal('show')
                   .find('#updateModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#sheet_samples_grid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#createModalButton').on('click',function(e){

        $('#createSampleModal').modal('show')
                   .find('#modalConent')
                   .load($(this).attr('value'));
});

JS;
$this->registerJs($script);
?>
