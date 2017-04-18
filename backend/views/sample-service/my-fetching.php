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


$this->title = Yii::t('backend', 'My Fetching');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('/sample-service/_tab_view'); ?>

    <br>
    <br>
    <br>
    <div class="in-library-index">

        <?php
        $gridColumns = [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style',
                    'order' => DynaGrid::ORDER_FIX_LEFT]
            ],
            [
                'attribute' => 'received_at',
                'value' => 'received_at',
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
//            [
//                'label' => Yii::t('backend', 'Sample Code'),
//                'value' => 'sample.serial_number',
//                'vAlign' => 'middle',
//                'width' => '10%',
//            ],
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
            ],

            [
                'label' => Yii::t('backend', 'Barcode'),
                'value' => 'barcode',
                'vAlign' => 'middle',
                'width' => '10%',
            ],
            [
                'label' => Yii::t('backend', 'Weight'),
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
                'attribute' => Yii::t('backend', 'Sample Comment'),
                'value' => 'sample.comment',
                'vAlign' => 'middle',
                'width' => '20%'
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
                    'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Service') . '</h3>',
                    'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All  Services') . '</em></div>',
//                    'after' => Yii::$app->user->identity->isOwn('sample-service/fetch') ?
//                        Html::button(Yii::t('backend', 'Fetch'), ['class' => 'btn btn-success', 'id' => 'fetch-multiple']) : ''
                    'after' => Html::button(Yii::t('backend', 'Fetch'), ['class' => 'btn btn-success', 'id' => 'fetch-multiple'])
                ],
                'toolbar' => [
                    ['content' => '{dynagrid}'],
                ],
                'options' => ['id' => 'in_library_grid']
            ],
            'options' => ['id' => 'in_library_dynagrid'] // a unique identifier is important
        ]);

        ?>
    </div>
<?php
$urlPassMultiple = Url::to(['sample-service/fetch-multiple']);

$script = <<< JS
$(function () {
  $('#in_library_dynagrid').on('click','#fetch-multiple',function(e){

      var selectRows = $('#in_library_grid').yiiGridView('getSelectedRows');
      $.post( "{$urlPassMultiple}", 
              {
                 pk : selectRows
              },
              function(){
                $.pjax.reload({container:'#in_library_grid'});
              }
            );
   });

});

JS;
$this->registerJs($script);
?>