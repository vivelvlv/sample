<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sample;

/**
 * SampleSearch represents the model behind the search form about `common\models\Sample`.
 */
class SampleSearch extends Sample
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'test_sheet_id', 'user_id', 'type', 'status', 'created_at', 'completed_at'], 'integer'],
            [['name', 'serial_number','project_sn', 'comment'], 'safe'],
            [['weight'], 'number'],
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
        $query = Sample::find();

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
            'test_sheet_id' => $this->test_sheet_id,
            'user_id' => $this->user_id,
            'weight' => $this->weight,
//            'unit' => $this->unit,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'project_sn', $this->project_sn])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
