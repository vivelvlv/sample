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

            if ($link === $actLink) {
                return true;
            } else {
                $location = strpos($actLink, '/');
                if ($location <= 0) {
                    return $actLink === $controller;
                }
                return false;
            }
        }

        ?>

        <?= Html::a(Yii::t('backend', 'Service'), ['service/index'], ['class' => isActive('service') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Device'), ['device/index'], ['class' => isActive('device') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

        <?= Html::a(Yii::t('backend', 'Services Type'), ['service-type/index'], ['class' => isActive('service-type') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

       <?= Html::a(Yii::t('backend', 'Sample Type'), ['sample-type/index'], ['class' => isActive('sample-type') ?
            'btn btn-primary' :
            'btn btn-default']) ?> 


        <?= Html::a(Yii::t('backend', 'Sample Unit'), ['sample-unit/index'], ['class' => isActive('sample-unit') ?
            'btn btn-primary' :
            'btn btn-default']) ?>
        <?= Html::a(Yii::t('backend', 'Terms'), ['article/terms'], ['class' => isActive('article/terms') ?
            'btn btn-primary' :
            'btn btn-default']) ?>
        <?= Html::a(Yii::t('backend', 'Announce'), ['article/announce'], ['class' => isActive('article/announce') ?
            'btn btn-primary' :
            'btn btn-default']) ?>

    </div>
</div>