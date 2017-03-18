<?php
use yii\helpers\Html;

?>

<div class="tab-view">
    <div class="btn-group pull-right">

        <?php

        function isActive($actLink)
        {
            $controller = Yii::$app->controller->id;
            $action = Yii::$app->controller->action->id;
            $link = $controller . '/' . $action;
            return $link === $actLink;
        }

        ?>

        <?= Html::a(Yii::t('backend', 'My Fetching'), ['sample-service/my-fetching'], ['class' => isActive('sample-service/my-fetching') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'My Test'), ['sample-service/my-test'], ['class' => isActive('sample-service/my-test') ?
            'btn btn-primary' :
            'btn btn-default']) ?>


        <?= Html::a(Yii::t('backend', 'Complete List'), ['sample-service/complete'], ['class' => isActive('sample-service/complete') ?
            'btn btn-primary' :
            'btn btn-default']) ?>


    </div>
</div>