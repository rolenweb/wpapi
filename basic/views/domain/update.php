<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Domain */

$this->title = 'Update Domain: ' . $model->domain;
$this->params['breadcrumbs'][] = ['label' => 'Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="domain-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
