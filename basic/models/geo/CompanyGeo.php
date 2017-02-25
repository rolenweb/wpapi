<?php

namespace app\models\geo;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "company_geo".
 *
 * @property integer $id
 * @property integer $company_id
 * @property double $lon
 * @property double $lat
 * @property integer $created_at
 * @property integer $updated_at
 */
class CompanyGeo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_geo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'created_at', 'updated_at'], 'integer'],
            [['lon', 'lat'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'lon' => 'Lon',
            'lat' => 'Lat',
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

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
