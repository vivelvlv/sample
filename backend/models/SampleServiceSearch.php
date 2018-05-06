<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SampleService;
use common\models\TestSheet;
use common\models\Sample;

/**
 * SampleServiceSearch represents the model behind the search form about `common\models\SampleService`.
 */
class SampleServiceSearch extends SampleService
{
    public $barcodes;

    public $deliver_barcodes;

    public $fetch_barcodes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'service_id', 'action_user', 'status'], 'integer'],
            [['sample_id'], 'safe'],
            [['barcodes', 'deliver_barcodes', 'fetch_barcodes', 'test_sheet_id', 'created_at', 'received_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SampleService::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('testSheet');
        // $query->joinWith('sample');

        // grid filtering conditions
        $query->andFilterWhere([
            //'sample_id' => $this->sample_id,
            'service_id' => $this->service_id,
            'action_user' => $this->action_user,
            SampleService::tableName() . '.user_id' => $this->user_id,
            SampleService::tableName() . '.status' => $this->status,
        ]);

        if (!$this->IsNullOrEmptyString($this->barcodes)) {
            $barcodeArray = explode("\r\n", $this->barcodes);
            if (!empty($barcodeArray)) {
                $query->andFilterWhere(['in', TestSheet::tableName() . '.barcode', $barcodeArray]);
            }
        }

        if (!$this->IsNullOrEmptyString($this->deliver_barcodes)) {
            $barcodeArray = explode("\r\n", $this->deliver_barcodes);
            if (!empty($barcodeArray)) {
                $query->andFilterWhere(['in', SampleService::tableName() . '.barcode', $barcodeArray]);
            }
        }

        if (!$this->IsNullOrEmptyString($this->fetch_barcodes)) {
            $barcodeArray = explode("\r\n", $this->fetch_barcodes);
            if (!empty($barcodeArray)) {
                $query->andFilterWhere(['in', SampleService::tableName() . '.barcode', $barcodeArray]);
            }
        }
        if (!$this->IsNullOrEmptyString($this->test_sheet_id)) {
            $query->andFilterWhere(['like', TestSheet::tableName() . '.name', $this->test_sheet_id]);
        }

        if (!$this->IsNullOrEmptyString($this->sample_id)) {
            $sampleList = Sample::find()->select(['id'])->andFilterWhere(['like', "name", $this->sample_id])->
            orFilterWhere(['like', "serial_number", $this->sample_id])->asArray()->all();
            $arrayList = [];
            foreach ($sampleList as $item) {
                array_push($arrayList, $item['id']);
            }
            if (count($arrayList) > 0) {
                $query->andFilterWhere(['in', 'sample_id', $arrayList]);
            }
        }

        if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', SampleService::tableName() . '.created_at', strtotime($start_date), strtotime($end_date) + 24 * 60 * 60]);
        }

        if (!is_null($this->received_at) && strpos($this->received_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->received_at);
            $query->andFilterWhere(['between', SampleService::tableName() . '.received_at', strtotime($start_date), strtotime($end_date) + 24 * 60 * 60]);
        }
        return $dataProvider;
    }

    function IsNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }
}
