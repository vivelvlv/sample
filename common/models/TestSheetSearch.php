<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TestSheet;

/**
 * TestSheetSearch represents the model behind the search form about `common\models\TestSheet`.
 */
class TestSheetSearch extends TestSheet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'storage_condition', 'service_type', 'report_fetch_type', 'sample_handle_type', 'status', 'created_at', 'completed_at'], 'integer'],
            [['name', 'comment', 'barcode'], 'safe'],
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
        $query = TestSheet::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                 'defaultOrder'=>[
                    'created_at'=>SORT_DESC,
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'storage_condition' => $this->storage_condition,
            'service_type' => $this->service_type,
            'report_fetch_type' => $this->report_fetch_type,
            'sample_handle_type' => $this->sample_handle_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'barcode', $this->barcode]);

        return $dataProvider;
    }
}
