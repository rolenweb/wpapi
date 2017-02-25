<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */

echo Breadcrumbs::widget([
    'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
    'links' => [
        /*[
            'label' => 'Catalog',
            'url' => ['catalog/index'],
			'title' => 'Catalog'
        ],*/
        [
            'label' => $model->title,
            'url' => ['toyota-tacoma/frame','id' => $model->id],
            'title' => $model->title,
        ],
        [
            'label' => $frame->title,
            'url' => ['toyota-tacoma/complectation','id' => $frame->id],
            'title' => $frame->title,
        ],
        [
            'label' => $complectation->complectation,
        ],
    ],
]);
?>

<ul>
    <li>
        <?= Html::a('Engine, fuel system and tools',['group-parts','type' => 1, 'id' => $complectation->id]) ?>
    </li>
    <li>
        <?= Html::a('Transmission and chassis',['group-parts','type' => 2, 'id' => $complectation->id]) ?>
    </li>
    <li>
        <?= Html::a('Body and interior',['group-parts','type' => 3, 'id' => $complectation->id]) ?>
    </li>
    <li>
        <?= Html::a('Electrics',['group-parts','type' => 4, 'id' => $complectation->id]) ?>
    </li>
</ul>