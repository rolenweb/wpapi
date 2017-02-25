<?php

namespace app\models\toyota\tacoma;

use Yii;

/**
 * This is the model class for table "complectation_option".
 *
 * @property integer $id
 * @property integer $complectation_id
 * @property string $title
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class ComplectationOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complectation_option';
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
            [['complectation_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'complectation_id' => 'Complectation ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getComplectation()
    {
        return $this->hasOne(Complectation::className(), ['id' => 'complectation_id']);
    }
}
