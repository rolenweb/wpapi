<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "domain".
 *
 * @property integer $id
 * @property string $domain
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Domain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['domain','login','pass'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'description' => 'Description',
            'status' => 'Status',
            'login' => 'Login',
            'pass' => 'Password',
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

    public function ddStatus()
    {
        return [
            1 => 'Wating',
            2 => 'Process',
            3 => 'Finished',
        ];
    }

    public static function dd()
    {
        return ArrayHelper::map(self::find()->all(),'id','domain');
    }
}
