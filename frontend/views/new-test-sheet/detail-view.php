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
use yii\helpers\Url;

$this->title = '详情';
$this->params['breadcrumbs'][] = $this->title;

$optionsArray = array(
    'elementId' => 'showBarcode', /* div or canvas id*/
    'value' => $barcode, /* value for EAN 13 be careful to set right values for each barcode type */
    'type' => 'code128',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

);
?>
    <div id="printContent">

        <table class="table table-bordered" width="100%">
            <tr style="text-align: center">
                <td colspan="5" style="border-right: 0px;border-top: 0px;"><?= Yii::t('frontend', 'Test Page Order'); ?>
                    (<?= $testSheet->name ?>)
                </td>
                <td style="border-left: 0px;border-top: 0px; width: 40%">
                    <div id="showBarcode" style="align-content: center"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">委托方名称:</td>
                <td colspan="4"><?= $user->company_name ?></td>
            </tr>
            <tr>
                <td style="border-right: 0px">联系人:</td>
                <td style="border-left: 0px" colspan="2"><?= $user->user_name ?></td>
                <td style="border-right: 0px">邮箱:</td>
                <td style="border-left: 0px" colspan="2"><?= $user->email ?></td>
            </tr>
            <tr>
                <td style="border-right: 0px">电话:</td>
                <td style="border-left: 0px" colspan="2"><?= $user->mobile_phone ?></td>
                <td style="border-right: 0px">传真:</td>
                <td style="border-left: 0px" colspan="2">--</td>
            </tr>
            <tr>
                <td style="border-right: 0px">地址:</td>
                <td style="border-left: 0px"
                    colspan="2"><?= $user->getUserProvince()->one()->province_name . " " . $user->getUserCity()->one()->city_name . " " . $user->detail_address ?></td>
                <td style="border-right: 0px">邮编:</td>
                <td style="border-left: 0px" colspan="2">--</td>
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
            <?php foreach ($printList as $item): ?>
                <tr>
                    <td><?= $item->name ?></td>
                    <td><?= $item->serial_number ?></td>
                    <td colspan="2"><?= $item->serviceList ?></td>
                    <td><?= $item->weight ?></td>
                    <td colspan="2"><?= $item->comment ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <table class="table table-bordered" width="100%">
            <tr style="text-align: center">
                <td>存放条件</td>
                <td>样品处理方式</td>
            </tr>
            <tr style="text-align: center">
                <td><?= $testSheet->getStorageConditionText() ?></td>
                <td><?= $testSheet->getSampleHandleTypeText() ?></td>
        </table>
    </div>
<?php

echo BarcodeGenerator::widget($optionsArray);

$script = <<< JS

function  print(){
        $("#printContent").jqprint();
    }

JS;


?>