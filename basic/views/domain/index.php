<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DomainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Domains';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="domain-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Domain', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'domain',
            'login',
            'pass',
            'description:ntext',
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
