<?php
/**
 * Created by PhpStorm.
 * User: vive
 * Date: 2017/1/26
 * Time: 下午2:53
 * services List
 * @var $HeCiModel
 * @var $BiaoZhenModel
 * @var $HanLiangModel
 * @var $ShengWuModel
 */
?>

    <!-- Modal -->
    <div class="modal fade" id="myModalType" tabindex="-1" role="dialog" aria-labelledby="myModalLabelType">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabelType"><?= Yii::t('frontend', 'Please Choose Type'); ?></h4>
                </div>
                <div class="modal-body">
                    <!--modal begin-->
                    <div class="btn-group" role="group" aria-label="...">

                        <?php $type = \common\models\SampleType::sampleTypeItems();
                        foreach ($type as $key => $value):?>
                            <button type="button" class="btn btn-default type"
                                    value="<?= $key ?>"><?= $value; ?></button>
                        <?php endforeach; ?>
                    </div>
                    <!--modal end-->
                </div>
            </div>
        </div>
    </div>

    <script>

        var targetObject = "";
        var targetObject_ext = "";
        function showType(object, ext) {
            $('#myModalType').modal();                     // 以默认值初始化
            $('#myModalType').modal({keyboard: false});   // initialized with no keyboard
            $('#myModalType').modal('show');               // 初始化后立即调用 show 方法
            targetObject = object;
            targetObject_ext = ext;
        }
    </script>
<?php
$script = <<< JS

    $("#myModalType").on('click','button.type',function(event){
    var val = event.target.value;
    var name = event.target.innerHTML;
    $(targetObject).val(name);
    if(targetObject_ext){
        $(targetObject_ext).val(val);
    }
    $('#myModalType').modal('hide');  
});




JS;
$this->registerJs($script);
?>