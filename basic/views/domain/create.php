<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Domain */

$this->title = 'Create Domain';
$this->params['breadcrumbs'][] = ['label' => 'Domains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="domain-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
