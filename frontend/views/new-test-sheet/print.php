<?php
/**
 * Created by PhpStorm.
 * User: vive
 * Date: 2017/1/15
 * Time: 下午4:52
 * @var $barcode
 * @var $user
 * @var $extRows
 * @var $printList
 * @var $testSheet ;
 *
 */

use barcode\barcode\BarcodeGenerator;
use frontend\assets\PrintAsset;
use yii\helpers\Url;

PrintAsset::register($this);

$this->title = '南京明捷生物医药检测有限公司-' . Yii::t('frontend', 'Test Page Order');
$this->params['breadcrumbs'][] = $this->title;

$optionsArray = array(
    'elementId' => 'showBarcode', /* div or canvas id*/
    'value' => $barcode, /* value for EAN 13 be careful to set right values for each barcode type */
    'type' => 'code128',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

);
?>

    <style>

        body, article {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /*#footer {*/
        /*position: absolute;*/
        /*bottom: 0;*/
        /*left:0;*/
        /*height: 20px;*/
        /*}*/

        .table {
            width: 100%;
            max-width: 100%;
        }

        .table thead tr th,
        .table tbody tr th,
        .table tfoot tr th,
        .table thead tr td,
        .table tbody tr td,
        .table tfoot tr td {
            padding: 2px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }

        .table caption + thead tr:first-child th,
        .table colgroup + thead tr:first-child th,
        .table thead:first-child tr:first-child th,
        .table caption + thead tr:first-child td,
        .table colgroup + thead tr:first-child td,
        .table thead:first-child tr:first-child td {
            border-top: 0;
        }

        .table tbody + tbody {
            border-top: 1px solid #ddd;
        }

        .table .table {
            background-color: #fff;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .oneLine {
            border: 1px solid #ddd;
        }

        .table-bordered thead tr th,
        .table-bordered tbody tr th,
        .table-bordered tfoot tr th,
        .table-bordered thead tr td,
        .table-bordered tbody tr td,
        .table-bordered tfoot tr td {
            border: 1px solid #ddd;
        }

        .table-bordered thead tr th,
        .table-bordered thead tr td {
            border-bottom-width: 1px;
        }
    </style>

    <br/>
    <br/>
    <div id="printContent" style="font-size: small">

        <table class="table table-bordered" width="100%">
            <tr style="text-align: center">
                <td colspan="3" style="border-right: 0px;border-top: 0px;">
                    <small><?= $testSheet->name ?>&nbsp;&nbsp;(委托方: <?= $user->company_name ?>)</small>
                </td>
                <td style="border-left: 0px;border-top: 0px;" rowspan="2">
                    <div class="pull-right" id="showBarcode" style="align-content: center"></div>
                </td>
            </tr>
            <tr>
                <td style="border-right: 0px">联系人: <?= $user->user_name ?></td>
                <td style="border-right: 0px">邮箱: <?= $user->email ?></td>
                <td style="border-right: 0px">电话: <?= $user->mobile_phone ?></td>
            </tr>
            <tr>

                <td style="border-right: 0px" colspan="5">
                    地址: <?= $user->getUserProvince()->one()->province_name . " " . $user->getUserCity()->one()->city_name . " " . $user->detail_address ?></td>
            </tr>

        </table>

        <table class="table table-bordered" width="100%">
            <tr style="text-align: center">
                <td>样品名称</td>
                <td>样品编号</td>
                <td colspan="2">检测项目</td>
                <td>样品量(mg)</td>
                <td colspan="2">备注</td>
            </tr>
            <?php $i = 0; ?>
            <?php foreach ($printList as $item): ?>
                <?php $i++; ?>
                <tr>
                    <td><?= $item->name ?></td>
                    <td><?= $item->serial_number ?></td>
                    <td colspan="2"><?= $item->serviceList ?></td>
                    <td><?= $item->weight ?></td>
                    <td colspan="2"><?= $item->comment ?></td>
                </tr>
            <?php endforeach; ?>

            <?php for (; $i < 8; $i++): ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
            <?php endfor; ?>
        </table>
        <div class="oneLine">送样补充说明:<?= $testSheet->comment ?>
            <small class="pull-right">
                存放条件: <?= $testSheet->getStorageConditionText() ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;样品处理方式: <?= $testSheet->getSampleHandleTypeText() ?>
            </small>
        </div>
        <div class="oneLine">
            双方约定条款：<br>1.委托检测协议书的传真件、复印件均有效。检测方仅对来样负责，检测结果仅反映对该样品的评价，检测结果的使用所产生的直接或间接损失，检测方不承担任何责任。
            <br>2.委托方对送检样品中包含的任何已知的或潜在危害，
            <strong style="color:red">如放射性、有毒、爆炸性或农、兽药原药样品，应事先声明，否则应对产生的后果负全部责任</strong>
            。
            <br>3.检测方确认委托方付款后，应及时安排检测，须在预计完成报告日期内完成报告，如因不可抗力导致延误，应及时通知委托方并征得其同意。
            <br>4.本公司对一般样品保留到样品接收后10天，然后再进行实验室处置或客户领回。
            <br>5.自检测报告发出之日算起，若对检测报告有异议，请在5个工作日内向本公司提出，若当原样已被送样单位取回、或原样无法适当保存、或原样太少不足以复检时，本公司不受理样品的复检。
            <br>6.检测收费参照公司的收费标准，复杂样品、特殊样品分析或涉及分析方法研究的样品检测费根据实际情况由双方协商决定。
            <br>7. 检测周期开始时间以我公司接到样品的时间为准，若有特殊情况，我们会及时通知送样人员。
        </div>
        <div class="oneLine pull-bottom" id="footer">
            <br>&nbsp;
            <small class="pull-right">委托方(签名):______ &nbsp;&nbsp;日期: ______年____月____日
            </small>

        </div>
    </div>


    <table border="0" width="100%" style="margin-top: 20px;" class="should_remove">
        <tr style="text-align: center">
            <td><?php if (isset($create2Print) && $create2Print == true) : ?>
                    <?= \yii\helpers\Html::a(Yii::t('frontend', "Update"),
                        ['update', 'id' => $id], ['class' => 'btn btn-success']); ?>
                <?php endif; ?>
            </td>
            <td><input type="button" class="btn btn-success" onclick="print_custom()" value="提交且打印"/>
            </td>

            <td><?php if (isset($extRows) && count($extRows) > 0) : ?>
                    <?= \yii\helpers\Html::a(Yii::t('frontend', "Next"), ['print', 'id' => array_pop($extRows),
                        'extRows' => implode(",", $extRows)], ['class' => 'btn btn-success']); ?>
                <?php endif; ?>
            </td>

        </tr>
    </table>


<?php
$urlPrintSubmit = Url::to(['new-test-sheet/print-submit']);
?>

    <script>
        function print_custom() {
//            alert("dd");
            console.info("begin");
            $("#printContent").jqprint();
            $.post("<?=$urlPrintSubmit;?>",
                {
                    pk: <?=$id;?>
                },
                function () {
                }
            );
        }
    </script>

<?php

echo BarcodeGenerator::widget($optionsArray);


$script = <<< JS




JS;
$this->registerJs($script);

?>