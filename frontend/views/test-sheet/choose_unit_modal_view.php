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
use common\models\SampleUnit;

?>

    <!-- Modal -->
    <div class="modal fade" id="myModalUnit" tabindex="-1" role="dialog" aria-labelledby="myModalLabelUnit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabelUnit"><?= Yii::t('frontend', 'Please Choose Unit'); ?></h4>
                </div>
                <div class="modal-body">
                    <!--modal begin-->
                    <div class="btn-group" role="group" aria-label="...">

                        <?php $units = SampleUnit::sampleUnitItems();
                        foreach ($units as $key => $value):?>
                            <button type="button" class="btn btn-default unit"
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
        function showUnit(object, ext) {
            $('#myModalUnit').modal();                     // 以默认值初始化
            $('#myModalUnit').modal({keyboard: false});   // initialized with no keyboard
            $('#myModalUnit').modal('show');               // 初始化后立即调用 show 方法
            targetObject = object;
            targetObject_ext = ext;
        }
    </script>
<?php
$script = <<< JS

    $("#myModalUnit").on('click','button.unit',function(event){
    var val = event.target.value;
    var name = event.target.innerHTML;
    $(targetObject).val(name);
    if(targetObject_ext){
        $(targetObject_ext).val(val);
    }
    $('#myModalUnit').modal('hide');  
});




JS;
$this->registerJs($script);
?>