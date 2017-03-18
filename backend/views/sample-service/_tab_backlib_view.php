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

        <?= Html::a(Yii::t('backend', 'Normal Back List'), ['sample-service/normal-back'], ['class' => isActive('sample-service/normal-back') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Exception Back List'), ['sample-service/exception-back'], ['class' => isActive('sample-service/exception-back') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

    </div>
</div>