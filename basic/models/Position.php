<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\geo\City;
use app\models\toyota\tacoma\Parts;
/**
 * This is the model class for table "position".
 *
 * @property integer $id
 * @property integer $domain_id
 * @property string $object
 * @property integer $object_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['domain_id', 'object_id', 'created_at', 'updated_at','status'], 'integer'],
            [['object'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain_id' => 'Domain ID',
            'object' => 'Object',
            'object_id' => 'Object ID',
            'status' => 'Status',
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

    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    public function getObjectModel()
    {
        switch ($this->object) {
            case 'city':
                return $this->hasOne(City::className(), ['id' => 'object_id']);
                break;

            case 'parts':
                return $this->hasOne(Parts::className(), ['id' => 'object_id']);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function getNext()
    {
        switch ($this->object) {
            case 'city':
                $nextObject = City::find()
                    ->joinWith(['companies'])
                    ->where(
                        [
                            'and',
                                [
                                    'is not','company.city_id',null
                                ],
                                [
                                    '>','city.id',$this->object_id
                                ]
                        ]
                    )->limit(1)->one();
                if (empty($nextObject)) {
                    $this->object_id = null;
                }else{
                    $this->object_id = $nextObject->id;
                }
                break;

            case 'parts':
                $nextObject = Parts::find()
                    ->where(
                        [
                            'and',
                                [
                                    '>','id',$this->object_id
                                ],
                                [
                                    'is not','pnc',null
                                ],
                                [
                                    'is not','oem',null
                                ],
                                [
                                    'is not','required',null
                                ],
                                [
                                    'is not','period',null
                                ],
                                [
                                    'is not','name',null
                                ],
                                [
                                    'is not','applicability',null
                                ],
                        ]
                    )->limit(1)->one();
                if (empty($nextObject)) {
                    $this->object_id = null;
                }else{
                    $this->object_id = $nextObject->id;
                }
                break;
            
            default:
                # code...
                break;
        }
        $this->save();
        return $this;
    }

    public function getProcess()
    {
        switch ($this->object) {
            case 'city':
                $totalCity = City::find()->count();
                $usedCity = City::find()->where(['<','id',$this->object_id])->count();
                $process = (empty($usedCity) === 0) ? 0 : round($usedCity/$totalCity*100);
                return $process;
                break;

            case 'parts':
                $totalCity = Parts::find()->count();
                $usedCity = Parts::find()->where(['<','id',$this->object_id])->count();
                $process = (empty($usedCity) === 0) ? 0 : round($usedCity/$totalCity*100);
                return $process;
                break;
            
            default:
                # code...
                break;
        }
        
    }

    public function ddStatus()
    {
        return [
            1 => 'Wating',
            2 => 'Process',
            3 => 'Finished',
        ];
    }

    public function getStatusName()
    {
        switch ($this->status) {
            case 1:
                return 'wating';
                break;

            case 2:
                return 'process';
                break;

            case 3:
                return 'finished';
                break;
            
            default:
                # code...
                break;
        }
    }    
}
