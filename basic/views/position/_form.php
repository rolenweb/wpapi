<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Domain;

/* @var $this yii\web\View */
/* @var $model app\models\Position */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'domain_id')->dropDownList(Domain::dd()) ?>

    <?= $form->field($model, 'object')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'object_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList($model::ddStatus()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
