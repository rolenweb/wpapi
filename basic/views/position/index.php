<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-index">

    
    <p class="text-right">
        <?= Html::a('Create Position', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            
            [
                'attribute'=>'domain_id',
                'label' => 'Domain',
                'content'=>function($data){
                    return (empty($data->domain) === false) ? $data->domain->domain : 'no set';
                }
                
            ],
            'object',
            [
                'attribute'=>'object_id',
                'label' => 'Name of object',
                'content'=>function($data){
                    return (empty($data->objectModel) === false) ? $data->objectModel->name.'('.$data->process.'%)' : 'no set';
                }
                
            ],
            [
                'attribute'=>'status',
                'label' => 'Status',
                'content'=>function($data){
                    return $data->statusName;
                }
                
            ],
            [
                'attribute'=>'created_at',
                'label' => 'Created',
                'content'=>function($data){
                    return date("d/m/Y H:i:s",$data->created_at);
                }
                
            ],
            [
                'attribute'=>'updated_at',
                'label' => 'Updated',
                'content'=>function($data){
                    return date("d/m/Y H:i:s",$data->updated_at);
                }
                
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
