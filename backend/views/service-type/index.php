<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Sample Type');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('/sample/_tab_view'); ?>
<br>

<div class="sample-type-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>
    <br>
    <?php

    //     karnbrockgmbh\modal\Modal::begin([
    //     'id' => 'createModel',
    //     'url' => Url::to(['/partner/default/create']), // Ajax view with form to load
    //     'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
    //     // ... any other yii2 bootstrap modal option you need
    // ]);
    //   karnbrockgmbh\modal\Modal::end();


    //create
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service Type') . ' </h4>',
        'id' => 'createModel',
        'size' => 'modal-md',
    ]);
    echo "<div id='createModalConent'></div>";
    Modal::end();

    //update
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service Type') . ' </h4>',
        'id' => 'updateModal',
        'size' => 'modal-md',
    ]);
    echo "<div id='updateModalConent'></div>";
    Modal::end();

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service Type') . ' </h4>',
        'id' => 'viewModal',
        'size' => 'modal-md',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();

    ?>
    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'attribute' => 'name',
            'width' => '30%'
        ],
        [
            'attribute' => 'description',
            'width' => '40%'
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'is_show',
            'trueLabel' => Yii::t('backend', 'Shown'),
            'falseLabel' => Yii::t('backend', 'Hidden'),
            'vAlign' => 'middle',
            'width' => '20%'
        ],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{view} &nbsp;&nbsp;{update}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => 'sample_type_grid-pjax',
                        'name' => 'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
            ],
            'headerOptions' => ['width' => '100'],
        ],
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar' => [
            ['content' =>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['service-type/create']),
                    'title' => Yii::t('backend', 'Create Service Type'),
                    'class' => 'btn btn-success',
                    'id' => 'createSampleButton',
                ])
            ],
        ],
        'bordered' => true,
        'striped' => true,
        'hover' => true,
        'panel' => [

            //'heading'=>$heading,
        ],

        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Service Type') . '</h3>',
        ],
        'options' => ['id' => 'service_type_grid']
    ]);
    ?>


</div>


<?php
$script = <<< JS
var updateModal =  $('#updateModal');
$('#service_type_grid-pjax').on('click','a[name="modalUpdate"]',function(e){
       var url = $(this).attr('href');

        updateModal.kbModalAjax({
            url: url,
            ajaxSubmit: 'true',
        });
       
        updateModal.modal('show')
                   .find('.modal-body')
                   .load(url); 

        return false;
});

$('#updateModal').on('kbModalSubmit', function(event, data, status, xhr) 
{
    updateModal.modal('toggle')
    $.pjax.reload({container:'#service_type_grid-pjax'});
});



$('#service_type_grid-pjax').on('click','#createSampleButton',function(e){
        $('#createModel').modal('show')
                   .find('#createModalConent')
                   .load($(this).attr('value'));
        return false;
});

JS;
$this->registerJs($script);
?>




