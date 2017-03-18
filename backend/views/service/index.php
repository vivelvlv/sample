<?php

use common\models\ServiceType;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

use common\models\Device;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SampleUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('backend', 'Service');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('/sample/_tab_view'); ?>
<br>
<div class="service-index">
    <br>
    <br>
    <?php
    //create
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service') . ' </h4>',
        'id' => 'modal',
        'size' => 'modal-md',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo "<div id='modalConent'></div>";
    Modal::end();

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service') . ' </h4>',
        'id' => 'viewModal',
        'size' => 'modal-md',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();

    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Service') . ' </h4>',
        'id' => 'uploadModal',
        'size' => 'modal-md',
        'footer' => '<button type="button" id="closeUploadModal" class="btn btn-danger" data-dismiss="modal">Close</button>',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo "<div id='uploadModalConent'></div>";
    Modal::end();
    ?>

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'attribute' => 'catalog_number',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'name',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'description',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'price',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'device_id',
            'vAlign' => 'middle',
            'value' => 'device.name',
            'width' => '15%',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => Device::deviceAttributeLabel(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        [
            'attribute' => 'comment',
            'visible' => false,
        ],
        [
            'attribute' => 'type',
            'vAlign' => 'middle',
            'value' => 'serviceType.name',
            'width' => '15%',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ServiceType::typeAttributeLabel(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'is_show',
            'trueLabel' => Yii::t('backend', 'Shown'),
            'falseLabel' => Yii::t('backend', 'Hidden'),
            'vAlign' => 'middle',
            'visible' => false,
        ],
        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{view} &nbsp;&nbsp;{update}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                        'name' => 'modalView',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                        'name' => 'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
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
            'rowOptions' => function ($model) {
                if ($model->is_show == 0)
                    return ['class' => GridView::TYPE_DANGER];
            },
            'showPageSummary' => true,
            'bordered' => true,
            'striped' => true,
            'hover' => true,
            'pjax' => true,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Service') . '</h3>',
                'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All Services') . '</em></div>',

            ],
            'toolbar' => [
                ['content' => Yii::$app->user->identity->isOwn('service/create') ?
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['service/create']),
                        'class' => 'btn btn-success',
                        'id' => 'createModalButton', 'data-pjax' => '0'])
                    : ' '

                ],
                ['content' => '{dynagrid}'],
            ],

        ],
        'options' => ['id' => 'service_grid'] // a unique identifier is important
    ]);

    ?>

</div>


<?php
$script = <<< JS


$('#createModalButton').on('click',function(e){
        $('#modal').modal('show')
                   .find('#modalConent')
                   .load($(this).attr('value'));
});

$('#service_grid').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});


$('#service_grid').on('click','a[name="modalUpdate"]',function(e){
        $('#modal').modal('show')
                   .find('#modalConent')
                   .load($(this).attr('href'));
        return false;
});

JS;
$this->registerJs($script);
?>


