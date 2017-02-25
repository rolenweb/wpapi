<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\models\toyota\tacoma\PartsGroups;

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
            'url' => ['toyota-tacoma/group','id' => $complectation->id],
            'title' => $complectation->complectation,
        ],
        [
            'label' => PartsGroups::nameTypeByType($type),
        ],
    ],
]);
?>

<?php if (empty($groupParts) === false): ?>
    <ul>
        <?php foreach ($groupParts as $groupPart): ?>
            <li>
                <?= Html::a($groupPart->title,['toyota-tacoma/parts','id' => $groupPart->id]) ?>
            </li>       
        <?php endforeach ?> 
    </ul>
<?php endif ?>