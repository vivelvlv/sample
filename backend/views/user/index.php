<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

use common\models\AdminUser;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <br>
    <?php

    $column = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
        [
            'attribute' => 'user_name',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'mobile_phone',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'email',
            'format' => 'email',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'company_name',
            'vAlign' => 'middle',
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
            'visible' => false,
            'hAlign' => 'left',
            'vAlign' => 'middle',
        ],
        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{view} &nbsp;&nbsp;{update}',
            'visibleButtons' => [
                'update' => Yii::$app->user->identity->isOwn('user/update'),
            ],
            'vAlign' => 'middle',
            'width' => '140px',
            'order' => DynaGrid::ORDER_FIX_RIGHT
        ],
    ];
    ?>

    <?= DynaGrid::widget([
        'columns' => $column,
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
                if ($model->status == AdminUser::STATUS_DELETED)
                    return ['class' => GridView::TYPE_DANGER];
            },
            'showPageSummary' => true,
            'bordered' => true,
            'striped' => true,
            'hover' => true,
            'pjax' => true,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="fa fa-user-plus"></i>  ' . Yii::t('backend', 'Users') . '</h3>',
                'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All Users') . '</em></div>',

            ],
//            'toolbar' => [
//                ['content' => '{dynagrid}'],
//                '{export}',
//            ],

            'toolbar' => [
                ['content' => Yii::$app->user->identity->isOwn('user/signup') ?
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['signup'], ['class' => 'btn btn-success', 'data-pjax' => '0'])
                    : ' '

                ],
                ['content' => '{dynagrid}'],
                '{export}',
            ],

            'export' => [
                'fontAwesome' => true,
                'showConfirmAlert' => false,
                'target' => GridView::TARGET_BLANK
            ],
            'exportConfig' => [
                GridView::CSV => ['filename' => Yii::t('backend', 'Columns')],
                GridView::HTML => ['filename' => Yii::t('backend', 'Columns')],
                //GridView::PDF => ['filename' => Yii::t('backend', 'Samples')],
                GridView::EXCEL => ['filename' => Yii::t('backend', 'Columns')],
                //GridView::TEXT => ['filename' => Yii::t('backend', 'Samples')]
            ]
        ],
        'options' => ['id' => 'user_index_grid'] // a unique identifier is important
    ]); ?>

</div>
