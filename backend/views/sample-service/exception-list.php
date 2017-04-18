<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

use common\models\Service;
use common\models\User;

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('backend', 'Exception List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exception-list-index">

    <?php
    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style',
                'order' => DynaGrid::ORDER_FIX_LEFT]
        ],
        [
            'attribute' => 'created_at',
            'value' => 'created_at',
            'format' => 'date',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => ([
                'presetDropdown' => TRUE,
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                    'opens' => 'left'
                ]
            ]),
            'hAlign' => 'left',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'sample_id',
            'value' => 'sample.name',
            'visible' => true,
            'vAlign' => 'middle',
        ],
//        [
//               'label'=> Yii::t('backend','Sample Code'),
//            'value' => 'sample.serial_number',
//            'vAlign' => 'middle',
//            'width' => '10%',
//        ],
        [
            'attribute' => 'service_id',
            'vAlign' => 'middle',
            'value' => 'service.name',
            'width' => '15%',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => Service::serviceItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        [
            'label' => Yii::t('backend', 'Sample Type'),
            'value' => 'sample.sampleType.name',
            'vAlign' => 'middle',
            'width' => '10%',
            'visible' => false,
        ],

        [
               'label'=> Yii::t('backend','Sample Type'),
            'value' => 'sample.sampleType.name',
            'vAlign' => 'middle',
            'width' => '10%',
        ],
        [
              'label'=>Yii::t('backend','Weight'),
            'value' => function ($model, $key, $index, $column) {
                if (isset($model) && isset($model->sample) && isset($model->sample->weight)) {
                    return $model->sample->weight;
                } else {
                    return 0;
                }
            },
            'vAlign' => 'middle',
            'width' => '8%',
        ],
        [
            'attribute' => 'user_id',
            'vAlign' => 'middle',
            'width' => '15%',
            'value' => 'user.user_name',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => User::userAttributeLabel(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{exception-to-normal}',
            'buttons' => [
                'exception-to-normal' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('backend', 'Exception To Normal'),
                        'aria-label' => Yii::t('backend', 'Exception To Normal')
                    ];
                    return Html::a('<span class="fa  fa-share-square-o"></span>', $url, $options);
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
                    'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Exception Service') . '</h3>',
                    'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All Exception Services') . '</em></div>',

            ],
            'toolbar' => [
                ['content' => '{dynagrid}'],

            ],
            'options' => ['id' => 'exception_sample_grid']

        ],
        'options' => ['id' => 'exception_sample_dynagrid'] // a unique identifier is important
    ]);

    ?>

</div>
