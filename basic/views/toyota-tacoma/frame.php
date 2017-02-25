<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */

echo Breadcrumbs::widget([
    'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
    'links' => [
        /*[
            'label' => 'Tacoma',
            'url' => ['toyota-tacoma/index'],
			'title' => 'Tacoma'
        ],*/
        [
            'label' => $model->title,
        ],
    ],
]);
?>

<?php if (empty($frames) === false): ?>
	<ul>
		<?php foreach ($frames as $frame): ?>
			<li>
				<?= Html::a($frame->title,['toyota-tacoma/complectation','id' => $frame->id]) ?>
			</li>		
		<?php endforeach ?>	
	</ul>
<?php endif ?>