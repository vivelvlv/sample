<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

        <?= Yii::$app->user->identity->isOwn('role/create') ? Html::a(Yii::t('backend', 'Create Role'), ['create'], ['class' => 'btn btn-success'])
                                                            :'' ?>

    </p>

    <br>
  <?php 
    $gridColumns= [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute'=>'name',
              'width'=>'15%'
            ],

            [
              'attribute'=>'description',
              'width'=>'30%'
            ],
            [
               'attribute'=>'created_at',
               'format'=>'datetime',
               'filter'=>false,
            ],

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view} &nbsp;&nbsp;{update}',
              'buttons'=>[
                 'view'=> function($url,$model,$key)
                 {
                   $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                        'name'=>'modalView',
                    ];
                   return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                 },
                 'update'=> function($url,$model,$key)
                 {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                        'name'=>'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                 },
              ],
              'visibleButtons'=>[
                 'update'=>Yii::$app->user->identity->isOwn('role/update'),
              ],
              'headerOptions'=>['width'=>'100'],
            ],
        ];
    echo  GridView::widget([
          'dataProvider'=>$dataProvider,
          'filterModel'=>$searchModel,
          'columns'=>$gridColumns,
          'headerRowOptions'=>['class'=>'kartik-sheet-style'],
          'filterRowOptions'=>['class'=>'kartik-sheet-style'],
          'pjax'=>true, // pjax is set to always true for this demo
          // set your toolbar
          'toolbar'=> [
                 ['content'=>  Yii::$app->user->identity->isOwn('role/create') ?
                                   Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success','data-pjax' => '0'])
                                :' '
                        
                  ],
          ],
          'bordered'=>true,
          'striped'=>true,
          'hover'=>true,
          'panel'=>[
              
              //'heading'=>$heading,
          ],

          'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=>'<h3 class="panel-title"><i class="fa fa-user-plus"></i>  '.Yii::t('backend','Roles').'</h3>',
            'before' =>  '<div style="padding-top: 7px;"><em>*'.Yii::t('backend','Show All Roles').'</em></div>',
          ], 
          'options'=>['id'=>'role_grid']
      ]);
   ?>


</div>

