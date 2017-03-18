<?php

/* @var $this yii\web\View
 * @var $model
 */

$this->title = isset($model) ? $model->title : "未配置通告";
?>
<div class="site-index">


    <div class="jumbotron">
        <p class="lead"><?= isset($model) ? isset($model->content) ? $model->content : "" : "" ?></p>

    </div>
</div>
