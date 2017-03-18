<?php
use kartik\checkbox\CheckboxX;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use common\models\RegionProvince;
use common\models\RegionCity;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('backend','Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'user_name', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-user_name has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'User Name')]) ?>

    <?= $form->field($model, 'inputPassword', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-inputPassword has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'Password')]) ?>

    <?= $form->field($model, 'reInputPassword', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-reInputPassword has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-log-in form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'Retype password')]) ?>

    <?= $form->field($model, 'mobile_phone', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-mobile_phone has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-earphone form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'Mobile Phone')]) ?>


    <?= $form->field($model, 'email', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-email has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'Email')]) ?>

    <?= $form->field($model, 'company_name', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-company_name has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-th form-control-feedback"></span>{error}{hint}'
    ])->textInput(['placeholder' => Yii::t('backend', 'Company Name')]) ?>

    <?= $form->field($model, 'is_super')->widget(CheckboxX::classname(), [
        'initInputType' => CheckboxX::INPUT_CHECKBOX,
        'pluginOptions' => [
            'theme' => 'krajee-flatblue',
            'enclosedLabel' => true,
            'threeState' => false
        ]
    ])->label(false) ?>

    <legend class="text-info">
        <small> <?= Yii::t('backend', 'Address') ?></small>
    </legend>


    <?= $form->field($model, 'province', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-province has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>{error}{hint}'
    ])->dropDownList(
        ArrayHelper::map(RegionProvince::find()->all(), 'province_id', 'province_name'),
        [
            'prompt' => Yii::t('backend', 'Select Province'),
            'onchange' => '
                                 $.post("index.php?r=region/city&id=' . '"+$(this).val(),
                                  function(data){
                                      
                                      $("select#signupform-city").html(data);
                                  }
                                  );'
        ]) ?>


    <?= $form->field($model, 'city', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-city has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>{error}{hint}'
    ])->dropDownList(
        ArrayHelper::map(RegionCity::find()->all(), 'city_id', 'city_name'),
        [
            'prompt' => Yii::t('backend', 'Select City'),
        ]) ?>


    <?= $form->field($model, 'detail_address', [
        'options' => [
            'tag' => 'div',
            'class' => 'form-group field-Signup-detail_address has-feedback required'
        ],
        'template' => '{input}<span class="glyphicon glyphicon-home form-control-feedback"></span>{error}{hint}'
    ])->textArea(['rows' => 3, 'placeholder' => Yii::t('backend', 'Detail Address')]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Sign Up'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    $script = <<<JS

 $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
        });
      });
JS;
    $this->registerJS($script);
    ?>
