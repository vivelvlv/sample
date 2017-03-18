<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AdminUser;

/**
 * UserSearch represents the model behind the search form about `common\models\AdminUser`.
 */
class AdminUserSearch extends AdminUser
{
    //public $english_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status',  'leader_id', ], 'integer'],
            [['entry_date','leave_date','user_name', 'work_no', 'email', 'office_phone', 'mobile_phone'], 'safe'],
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
        $query = AdminUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                  'pageSize' => 15,
               ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'leader_id' => $this->leader_id,
            'leave_date' => $this->leave_date,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'work_no', $this->work_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'office_phone', $this->office_phone])
            ->andFilterWhere(['like', 'mobile_phone', $this->mobile_phone]);



        if ( ! is_null($this->entry_date) && strpos($this->entry_date, ' - ') !== false ) 
        {
            list($start_date, $end_date) = explode(' - ', $this->entry_date);
            $query->andFilterWhere(['between', 'entry_date', strtotime($start_date), strtotime($end_date)]);
        }

        return $dataProvider;
    }
}
