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

$this->title = Yii::t('backend', 'Admin Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>

        <?= Yii::$app->user->identity->isOwn('admin-user/create') ? Html::a(Yii::t('backend', 'Create Admin User'), ['create'], ['class' => 'btn btn-success'])
                                                            :'' ?>
    </p>
    <br>
      <?php

        $column= [
            ['class'=>'kartik\grid\SerialColumn', 'order'=>DynaGrid::ORDER_FIX_LEFT],
            [
               'attribute'=>'user_name',
               'vAlign'=>'middle',
            ],
            [
               'attribute'=>'work_no',
               'vAlign'=>'middle',
            ],
            [
                'attribute'=>'email',
                'format'=>'email',
                'vAlign'=>'middle',
            ],
            [
                'attribute'=>'mobile_phone',
                'vAlign'=>'middle',
                'visible'=>false,
            ],
            [
                'attribute'=>'office_phone',
                'vAlign'=>'middle',
                'visible'=>false,
            ],
            [
                'attribute'=>'leader_id', 
                'vAlign'=>'middle',
                'width'=>'15%',
                'value'=>'leader.user_name',

                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>AdminUser::userAttributeLabel(), 
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>' '],
                'format'=>'raw',
            ],
            [
                'label'=>'Lab',
                'value'=>function ($model, $key, $index, $column)
                  {
                    return $model->lab_building.' '.$model->lab_floor.' '.$model->lab_room;
                  },
                  'vAlign'=>'middle',
                  'visible'=>false,
            ],
            [
                'label'=>'Office',
                'value'=>function ($model, $key, $index, $column)
                  {
                    return $model->office_building.' '.$model->office_floor.' '.$model->office_room;
                  },
                  'vAlign'=>'middle',
                  'visible'=>false,
            ],
            [
                'attribute'=>'entry_date',
                'value'=>'entry_date',    
                'format'=>'date',            
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' =>([
                  'presetDropdown'=>TRUE,                
                  'convertFormat'=>true,                
                  'pluginOptions'=>[                                          
                      'locale'=>['format'=>'Y-m-d'],
                      'opens'=>'left'
                       ]
                 ]),
                'visible'=>false,
                'hAlign'=>'left', 
                'vAlign'=>'middle',
            ],
            [
                'attribute'=>'status', 
                'vAlign'=>'middle',
                'hAlign'=>'center',
                'width'=>'160px',
                'value'=>function ($model, $key, $index, $column)
                  {
                      if($model->status == AdminUser::STATUS_ACTIVE)
                      {
                          return GridView::ICON_ACTIVE ;
                      }
                      else
                      {
                          return GridView::ICON_INACTIVE;
                      }
                    
                  },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter'=>AdminUser::getStatusList(), 
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>' '],
                'format'=>'raw',
                'visible'=>false,
            ],
            [
                'label'=>'Role',
                'value'=>function ($model, $key, $index, $column)
                  {
                    return $model->getRoleName();
                  },
                  'vAlign'=>'middle',
                  'visible'=>false,
            ],
            ['class' => 'kartik\grid\ActionColumn',
              'template'=>'{view} &nbsp;&nbsp;{update}',
              'visibleButtons'=>[
                 'update'=>Yii::$app->user->identity->isOwn('admin-user/update'),
              ],
              'vAlign'=>'middle',
              'width'=>'140px',
              'order'=>DynaGrid::ORDER_FIX_RIGHT
            ],
        ];
      ?>

        <?= DynaGrid::widget([
          'columns'=>$column,
          'theme'=>'panel-info',
          'showPersonalize'=>true,
          'storage'=>'cookie',
          //'allowPageSetting'=>false,
          'allowThemeSetting'=>false,
          'allowFilterSetting'=>false,
          'allowSortSetting'=>false,
          'gridOptions'=>[
              'dataProvider'=>$dataProvider,
              'filterModel'=>$searchModel,
              'rowOptions' => function($model){
                      if(  $model->status == AdminUser::STATUS_DELETED) 
                        return ['class' => GridView::TYPE_DANGER];
                    },
              'showPageSummary'=>true,
              'bordered'=>true,
              'striped'=>true,
              'hover'=>true,
              'pjax'=>true,
              'panel'=>[
                  'heading'=>'<h3 class="panel-title"><i class="fa fa-user-plus"></i>  '.Yii::t('backend','Users').'</h3>',
                  'before' =>  '<div style="padding-top: 7px;"><em>*'.Yii::t('backend','Show All Users').'</em></div>',
                  
              ],        
              'toolbar' =>  [
                  ['content'=>  Yii::$app->user->identity->isOwn('admin-user/create') ?
                                   Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success','data-pjax' => '0'])
                                :' '
                        
                  ],
                  ['content'=>'{dynagrid}'],
                  '{export}',
              ],
              'export'=>[
                  'fontAwesome'=>true,
                  'showConfirmAlert'=>false,
                  'target'=>GridView::TARGET_BLANK
              ],
              'exportConfig'=>[
                  GridView::CSV => ['filename' => Yii::t('backend', 'Admin User')],
                  GridView::HTML => ['filename' => Yii::t('backend', 'Admin User')],
                  //GridView::PDF => ['filename' => Yii::t('backend', 'Samples')],
                  GridView::EXCEL =>['filename' => Yii::t('backend', 'Admin User')],
                  //GridView::TEXT => ['filename' => Yii::t('backend', 'Samples')]
              ]
          ],
          'options'=>['id'=>'user_index_grid'] // a unique identifier is important
    ]); ?>

</div>
