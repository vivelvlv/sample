<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SampleType;

/**
 * SampleTypeSearch represents the model behind the search form about `common\models\SampleType`.
 */
class SampleTypeSearch extends SampleType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_show'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = SampleType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => [
            //       'pageSize' => 3,
            //    ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_show' => $this->is_show,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
