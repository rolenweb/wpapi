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
        ],
    ],
]);
?>

<?php if (empty($complectations) === false): ?>
	<ul>
		<?php foreach ($complectations as $complectation): ?>
			<li>
                <ul class="list-inline">
                    <li>
                        <?= Html::a($complectation->complectation,['toyota-tacoma/group','id' => $complectation->id]) ?>        
                    </li>
                    <li>
                        <?= $complectation->engine ?>
                    </li>
                    <li>
                        <?= $complectation->period ?>
                    </li>
                    <li>
                        <?= $complectation->body ?>
                    </li>
                    <li>
                        <?= $complectation->grade ?>
                    </li>
                    <li>
                        <?= $complectation->transm ?>
                    </li>
                    <?php if (empty($complectation->options) === false): ?>
                        <?php foreach ($complectation->options as $option): ?>
                            <li>
                                <?= $option->title ?>
                            </li>
                        <?php endforeach ?>
                    <?php endif ?>
                    
                </ul>
				

			</li>		
		<?php endforeach ?>	
	</ul>
<?php endif ?>