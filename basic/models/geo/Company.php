<?php

namespace app\models\geo;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $keyword_id
 * @property string $ypid
 * @property string $title
 * @property string $street
 * @property string $postal
 * @property string $phone
 * @property string $category
 * @property string $image
 * @property string $website
 * @property integer $created_at
 * @property integer $updated_at
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'keyword_id', 'created_at', 'updated_at'], 'integer'],
            [['website'], 'string'],
            [['ypid', 'title', 'street', 'postal', 'phone', 'category', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'keyword_id' => 'Keyword ID',
            'ypid' => 'Ypid',
            'title' => 'Title',
            'street' => 'Street',
            'postal' => 'Postal',
            'phone' => 'Phone',
            'category' => 'Category',
            'image' => 'Image',
            'website' => 'Website',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    public function getGeo()
    {
        return $this->hasOne(CompanyGeo::className(), ['company_id' => 'id'])->inverseOf('company');
    }

    public function getAddress()
    {
        return $this->street.', '.$this->city->name.', '.$this->city->state->code.' '.$this->postal;
    }
}
