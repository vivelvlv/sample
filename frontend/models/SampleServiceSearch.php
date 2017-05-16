<?php

namespace frontend\models;

use common\models\Sample;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SampleService;

/**
 * SampleServiceSearch represents the model behind the search form about `common\models\SampleService`.
 */
class SampleServiceSearch extends SampleService
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'action_user', 'completed_at', 'status'], 'integer'],
            [['sample_id'], 'safe'],
            [['created_at'], 'safe'],
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

        // grid filtering conditions
        $query->andFilterWhere([
//            'sample_id' => $this->sample_id,
//            'service_id' => $this->service_id,
            'action_user' => $this->action_user,
//            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);
            $query->andFilterWhere(['between', SampleService::tableName() . '.created_at', strtotime($start_date), strtotime($end_date) + 24 * 60 * 60]);
        }

        if (isset($this->sample_id)) {
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

        $query->andFilterWhere(['like', 'document', $this->document]);

        return $dataProvider;
    }
}
