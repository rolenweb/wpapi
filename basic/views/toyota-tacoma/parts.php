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
            'url' => ['toyota-tacoma/group-parts','id' => $complectation->id,'type' => $type],
            'title' => PartsGroups::nameTypeByType($type),
        ],
        [
            'label' => $groupPart->title,
        ],
    ],
]);
?>

<?php if (empty($parts) === false): ?>
    <ul>
        <?php foreach ($parts as $part): ?>
            <li>
                <ul class="list-inline">
                    <li>
                        <?= $part->pnc ?>
                    </li>
                    <li>
                        <?= $part->oem ?>
                    </li>
                    <li>
                        <?= $part->required ?>
                    </li>
                    <li>
                        <?= $part->period ?>
                    </li>
                    <li>
                        <?= $part->name ?>
                    </li>
                    <li>
                        <?= $part->applicability ?>
                    </li>
                    <li>
                        <?= $part->price ?>
                    </li>
                </ul>
                
            </li>       
        <?php endforeach ?> 
    </ul>
    <table>
        <thead>
            <tr>
                <th>PNC</th>
                <th>OEM</th>
                <th>Required</th>
                <th>Period</th>
                <th>Name</th>
                <th>Applicability</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parts as $part): ?>
                <tr>
                    <td>
                        <?= $part->pnc ?>
                    </td>
                    <td>
                        <?= $part->oem ?>
                    </td>
                    <td>
                        <?= $part->required ?>
                    </td>
                    <td>
                        <?= $part->period ?>
                    </td>
                    <td>
                        <?= $part->name ?>
                    </td>
                    <td>
                        <?= $part->applicability ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>