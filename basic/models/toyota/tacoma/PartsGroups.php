<?php

namespace app\models\toyota\tacoma;

use Yii;

/**
 * This is the model class for table "parts_groups".
 *
 * @property integer $id
 * @property integer $complectation_id
 * @property integer $type
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 */
class PartsGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parts_groups';
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
            [['complectation_id', 'type', 'created_at', 'updated_at'], 'integer'],
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
            'complectation_id' => 'Complectation ID',
            'type' => 'Type',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getComplectation()
    {
        return $this->hasOne(Complectation::className(), ['id' => 'complectation_id']);
    }

    public function typeName()
    {
        switch ($this->type) {
            case 1:
                return 'Engine, fuel system and tools';
                break;
            case 2:
                return 'Transmission and chassis';
                break;
            case 3:
                return 'Body and interior';
                break;
            case 4:
                return 'Electrics';
                break;
            
            default:
                # code...
                break;
        }
    }

    public function getParts()
    {
        return $this->hasMany(Parts::className(), ['parts_groups_id' => 'id']);
    }

    public static function nameTypeByType($type)
    {
        switch ($type) {
            case 1:
                return 'Engine, fuel system and tools';
                break;
            case 2:
                return 'Transmission and chassis';
                break;
            case 3:
                return 'Body and interior';
                break;
            case 4:
                return 'Electrics';
                break;
            
            default:
                # code...
                break;
        }
    }
}
