<?php

namespace app\models\geo;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\geo\Company;

/**
 * CompanySearch represents the model behind the search form about `app\models\geo\Company`.
 */
class CompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'keyword_id', 'created_at', 'updated_at'], 'integer'],
            [['ypid', 'title', 'street', 'postal', 'phone', 'category', 'image', 'website'], 'safe'],
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
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'city_id' => $this->city_id,
            'keyword_id' => $this->keyword_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'ypid', $this->ypid])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'postal', $this->postal])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'website', $this->website]);

        return $dataProvider;
    }
}
