<?php
namespace frontend\models;


use Yii;
use yii\base\Model;
use common\models\Sample;
use common\models\SampleService;

/**
 * SampleService form
 */
class SampleServiceForm extends Model
{
    public $sample_id;
    public $services;

    public $colorTags;

    public function rules()
    {
        return [
            ['sample_id', 'integer'],
            [['services', 'colorTags'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'services' => Yii::t('frontend', 'Service'),
        ];
    }

    public function getServiceItems($sample_id)
    {
        return SampleService::find()->select(['service_id'])
            ->where(['sample_id' => $sample_id])
            ->column();

    }

    public function updateSampleServices($oldServices, $newServices)
    {
        $sample = Sample::findOne($this->sample_id);


        if (!is_array($oldServices)) {
            $oldServices = [];
        }
        if (!is_array($newServices)) {
            $newServices = [];
        }

        $rmServices = array_diff($oldServices, $newServices);
        $addServices = array_diff($newServices, $oldServices);


        if (!empty($rmServices)) {
            foreach ($rmServices as $serviceID) {
                $model = SampleService::findOne(['sample_id' => $this->sample_id, 'service_id' => $serviceID]);

                if ($model !== null) {
                    $model->delete();
                }
            }

        }

        if (!empty($addServices)) {
            foreach ($addServices as $serviceID) {
                $sampleService = new SampleService();
                $sampleService->sample_id = $this->sample_id;
                $sampleService->test_sheet_id = $sample->test_sheet_id;
                $sampleService->service_id = $serviceID;
                $sampleService->user_id = $sample->user_id;
                $sampleService->save();
            }
        }

        $sample_services = $sample->getSampleServices()->all();
        foreach ($sample_services as $sample_service) {
            $sample_service->status = SampleService::SAMPLESERVICE_STATUS_NO_SUBMIT;
            $sample_service->save();
        }

    }

}
