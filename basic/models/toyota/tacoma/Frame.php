<?php

namespace app\models\toyota\tacoma;

use Yii;

/**
 * This is the model class for table "frame".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 */
class Frame extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'frame';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('tacoma');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getModelCar()
    {
        return $this->hasOne(ModelCar::className(), ['id' => 'model_id']);
    }

    public function getComplectation()
    {
        return $this->hasMany(Complectation::className(), ['frame_id' => 'id'])->inverseOf('frame');
    }
}
