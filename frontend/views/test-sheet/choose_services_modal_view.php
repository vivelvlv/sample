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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?= Yii::t('frontend', 'Please Choose Service Items(Most Six Items)'); ?></h4>
                </div>
                <div class="modal-body">
                    <!--modal begin-->
                    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
                        <ul id="myTabs" class="nav nav-tabs" role="tablist">
                            <?php
                            $typeList = \common\models\ServiceType::typeAttributeLabel();
                            $type_key = [];
                            $type_value = [];
                            foreach ($typeList as $key => $value) {
                                $type_key[] = $key;
                                $type_value[] = $value;
                            }
                            $type_count = count($type_key);
                            ?>
                            <?php for ($i = 0; $i < $type_count; $i++): ?>
                                <li role="presentation" class="<?= $i == 0 ? 'active' : '' ?>"><a
                                        href="#tab<?= $i + 1; ?>"
                                        role="tab"
                                        data-toggle="tab"
                                        aria-controls="home" aria-expanded="true">
                                        <?= $type_value[$i]; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <?php
                            $ext_value = ["分离纯化", "药物分析"];
                            for ($j = $type_count; $j < $type_count + 2; $j++): ?>
                                <li role="presentation" class="<?= $j == 0 ? 'active' : '' ?>"><a
                                        href="#tab<?= $j + 1; ?>"
                                        role="tab"
                                        data-toggle="tab"
                                        aria-controls="home" aria-expanded="true">
                                        <?= $ext_value[$j - $type_count]; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                        </ul>
                        <div id="myTabContent" class="tab-content">


                            <?php for ($j = 0; $j < $type_count; $j++): ?>
                                <div role="tabpanel" class="tab-pane fade <?= $j == 0 ? 'active in' : ''; ?>"
                                     id="tab<?= $j + 1; ?>">
                                    <div class="select_users">
                                        <table class="table">
                                            <tbody>
                                            <?php
                                            $list = \common\models\Service::serviceItemsList($j + 1);
                                            $list_key = [];
                                            $list_value = [];
                                            foreach ($list as $key => $value) {
                                                $list_key[] = $key;
                                                $list_value[] = $value;
                                            }

                                            $count = count($list_key);
                                            ?>
                                            <?php for ($i = 0; $i < $count; $i = $i + 5): ?>
                                                <tr>
                                                    <td width="20%">
                                                        <?php if ($i < $count): ?>
                                                            <input type="checkbox" name="Items[]"
                                                                   ext_text="<?= $list_value[$i]; ?>"
                                                                   value="<?= $list_key[$i]; ?>">
                                                            <?= trim($list_value[$i]) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php if ($i + 1 < $count): ?>
                                                            <input type="checkbox" name="Items[]"
                                                                   ext_text="<?= $list_value[$i + 1]; ?>"
                                                                   value="<?= $list_key[$i + 1]; ?>">
                                                            <?= trim($list_value[$i + 1]) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php if ($i + 2 < $count): ?>
                                                            <input type="checkbox" name="Items[]"
                                                                   ext_text="<?= $list_value[$i + 2]; ?>"
                                                                   value="<?= $list_key[$i + 2]; ?>">
                                                            <?= trim($list_value[$i + 2]) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php if ($i + 3 < $count): ?>
                                                            <input type="checkbox" name="Items[]"
                                                                   ext_text="<?= $list_value[$i + 3]; ?>"
                                                                   value="<?= $list_key[$i + 3]; ?>">
                                                            <?= trim($list_value[$i + 3]) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td width="20%">
                                                        <?php if ($i + 4 < $count): ?>
                                                            <input type="checkbox" name="Items[]"
                                                                   ext_text="<?= $list_value[$i + 4]; ?>"
                                                                   value="<?= $list_key[$i + 4]; ?>">
                                                            <?= trim($list_value[$i + 4]) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <?php endfor; ?>

                            <?php
                            for ($j = $type_count; $j < $type_count + 2; $j++): ?>
                                <div role="tabpanel" class="tab-pane fade <?= $j == 0 ? 'active in' : ''; ?>"
                                     id="tab<?= $j + 1; ?>">
                                    <?php if ($j - $type_count == 0): ?>
                                        <div class="select_users">
                                            <ul>
                                                <li>
                                                    1. 产品纯化
                                                </li>
                                                <li>
                                                    2. 杂质纯化
                                                </li>
                                                <li>
                                                    3. 分离工艺开发
                                                </li>
                                                <li>
                                                    4. 手性拆分
                                                </li>
                                            </ul>
                                            <div style="color:#d81b60">
                                                联系人: 朱子丰, 电话: 186-2153-7715, 邮箱: zifeng.zhu@biopharmpt.com
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="select_users">
                                            <ul>
                                                <li>
                                                    1. 原料药有关物质及含量研究
                                                </li>
                                                <li>
                                                    2. 药物溶剂残留研究
                                                </li>
                                                <li>
                                                    3. 药物基因毒残留研究
                                                </li>
                                                <li>
                                                    4. 药物重金属残留研究
                                                </li>
                                                <li>
                                                    5. 制剂溶出度研究
                                                </li>
                                                <li>
                                                    6. 制剂有关物质及含量研究
                                                </li>

                                            </ul>
                                            <div style="color:#d81b60">
                                                联系人: 朱子丰, 电话: 186-2153-7715, 邮箱: zifeng.zhu@biopharmpt.com
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <!--modal end-->

                </div>
                <div class="modal-footer">
                    <p style="display: inline-block;" class="pull-left" id="result_hint"></p>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('frontend', 'Close'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var targetObject = "";
        var targetObject_ext = "";
        function show(object, ext) {
            $('#myModal').modal();                     // 以默认值初始化
            $('#myModal').modal({keyboard: false});   // initialized with no keyboard
            $('#myModal').modal('show');               // 初始化后立即调用 show 方法
            targetObject = object;
            targetObject_ext = ext;
        }
    </script>
<?php
$script = <<< JS


function ltrim(str) {
        var pattern = new RegExp("^[//s]+","gi");
        return str.replace(pattern,"");
}

function rtrim(str) {
        var pattern = new RegExp("[//s]+$","gi");
        return str.replace(pattern,"");
}

function trim(str) {
        return rtrim(ltrim(str));
}


var choose_result = [];
var choose_item = [];
var choose_description = [];
    $("#result_hint").html("您已经选择 0 项");
$("#myTabContent").on('click','input[name="Items[]"]',function(event){
    var checked = event.target.checked;
    var val = event.target.value;
    var ext_text = $(event.target).attr('ext_text');

    var temp = trim(ext_text);
    choose_description[val] = temp;
    if(checked){
        choose_item[val] = true;
        if (choose_result[targetObject]) {
                if(choose_result[targetObject] >= 6){
                    event.target.checked = false;
                    choose_item[val] = false;
                    return;
                }
                choose_result[targetObject]++;
            } else {
                choose_result[targetObject] = 1;
            }
    }else{
        if(choose_item[val]== true){
            choose_item[val] = false;
        }
        if (choose_result[targetObject]) {
                choose_result[targetObject]--;
            } else {
                choose_result[targetObject] = 0;
            }
    }
    $("#result_hint").html("您已经选择 "+choose_result[targetObject]+" 项");

});


$("#myModal").on('hide.bs.modal',function() {
    if(choose_item && choose_item.length > 0){
        var j = 0;
        var result = [];
        var description = [];
        for(var i = 0;i < choose_item.length;i++){
            if(choose_item[i] == true){
                description[j] = choose_description[i];
                result[j++] = i;
                
            }
        }
        $(targetObject).val(description);
        $(targetObject).attr('ext_id',result);
        if(targetObject_ext){
            $(targetObject_ext).val(result);
        }
    }
    
    $('input[name="Items[]"]').removeAttr("checked");
    choose_item = [];
    choose_result = [];
    $("#result_hint").html("您已经选择 0 项");
});


JS;
$this->registerJs($script);
?>