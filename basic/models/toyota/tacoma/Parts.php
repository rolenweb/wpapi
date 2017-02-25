<?php

namespace app\models\toyota\tacoma;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * This is the model class for table "parts".
 *
 * @property integer $id
 * @property integer $parts_groups_id
 * @property string $pnc
 * @property string $oem
 * @property string $required
 * @property string $period
 * @property string $name
 * @property string $applicability
 * @property string $price
 * @property integer $created_at
 * @property integer $updated_at
 */
class Parts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parts';
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
            [['parts_groups_id', 'created_at', 'updated_at'], 'integer'],
            [['applicability'], 'string'],
            [['pnc', 'oem', 'required', 'period', 'name', 'price'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parts_groups_id' => 'Parts Groups ID',
            'pnc' => 'Pnc',
            'oem' => 'Oem',
            'required' => 'Required',
            'period' => 'Period',
            'name' => 'Name',
            'applicability' => 'Applicability',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGroup()
    {
        return $this->hasOne(PartsGroups::className(), ['id' => 'parts_groups_id']);
    }

    public function GetStructure()
    {
        return
            [
                0 => [
                    'title' => $this->group->complectation->frame->title,
                    'type' => 'frame',
                    'description' => null
                ],
                1 => [
                    'title' => $this->group->complectation->complectation,
                    'type' => 'complectation',
                    'description' => $this->group->complectation->htmlDesctiption
                ],
                2 => [
                    'title' => $this->group->typeName(),
                    'type' => 'group',
                    'description' => null
                ],
                3 => [
                    'title' => $this->group->title,
                    'type' => 'subgroup',
                    'description' => null,
                ],

            ];
        
    }

    public function getHtmlShortDescription()
    {
        $out = null;
        $out .= Html::beginTag('div',['class' => 'panel panel-default']);
        $out .= Html::tag('div','OEM part number',['class' => 'panel-heading']);
        $out .= Html::tag('div',$this->oem,['class' => 'panel-body']);
        $out .= Html::endTag('div');
        $out .= Html::beginTag('div',['class' => 'panel panel-default']);
        $out .= Html::tag('div','Production period',['class' => 'panel-heading']);
        $out .= Html::tag('div',$this->period,['class' => 'panel-body']);
        $out .= Html::endTag('div');
        return $out;
    }

    public function getHtmlDescription()
    {
        $out = null;
        $out .= Html::beginTag('table',['class' => 'table part']);
        $out .= Html::beginTag('tbody');
        $out .= (empty($this->name) === false) ? Html::tag('tr',Html::tag('th','Name').Html::tag('td',$this->name)) : null;
        $out .= (empty($this->pnc) === false) ? Html::tag('tr',Html::tag('th','PNC').Html::tag('td',$this->pnc)) : null;
        $out .= (empty($this->oem) === false) ? Html::tag('tr',Html::tag('th','OEM part number').Html::tag('td',$this->oem)) : null;
        $out .= (empty($this->required) === false) ? Html::tag('tr',Html::tag('th','Required
per car').Html::tag('td',$this->required)) : null;
        $out .= (empty($this->period) === false) ? Html::tag('tr',Html::tag('th','Production period').Html::tag('td',$this->period)) : null;
        $out .= (empty($this->applicability) === false) ? Html::tag('tr',Html::tag('th','Applicability').Html::tag('td',$this->applicability)) : null;
        $out .= Html::endTag('tbody');
        $out .= Html::endTag('table');
        return $out;
    }

    public function clearPrice()
    {
        if (stripos($this->price, 'to') === false) {
            return trim(trim(str_replace('USD', '', $this->price),"\xC2\xA0\n"));
        }else{
            preg_match('/(.*)to(.*)USD/',$this->price,$matches);
            
            return (empty($matches[1]) === false) ? trim(trim($matches[1],"\xC2\xA0\n")) : null;
        }
    }

    
}
