<?php

namespace app\controllers;

use Yii;
use app\models\toyota\tacoma\ModelCar;
use app\models\toyota\tacoma\Frame;
use app\models\toyota\tacoma\Complectation;
use app\models\toyota\tacoma\PartsGroups;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ToyotaTacomaController extends \yii\web\Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','frame','complectation','group-parts','parts'],
                'rules' => [
                    [
                        'actions' => ['index','frame','complectation','group-parts','parts'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->accessPage(Yii::$app->request->userIP);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
    	$model = ModelCar::find()->where(['like','title','Tacoma'])->limit(1)->one();
    	
        return $this->render('index',['model' => $model]);
    }

    public function actionFrame($id)
    {
    	$model = ModelCar::findOne($id);
    	
        return $this->render('frame',
        	[
        		'model' => $model,
        		'frames' => $model->frames
        	]
        );
    }

    public function actionComplectation($id)
    {
    	$frame = Frame::findOne($id);
    	$model = $frame->modelCar;
    	
        return $this->render('complectation',
        	[
        		'model' => $model,
        		'frame' => $frame,
        		'complectations' => $frame->complectation
        	]
        );
    }

    public function actionGroup($id)
    {
    	$complectation = Complectation::findOne($id);
    	$frame = $complectation->frame;
    	$model = $frame->modelCar;
    	
        return $this->render('group',
        	[
        		'model' => $model,
        		'frame' => $frame,
        		'complectation' => $complectation
        	]
        );
    }

    public function actionGroupParts($id,$type)
    {
    	$complectation = Complectation::findOne($id);
    	$frame = $complectation->frame;
    	$model = $frame->modelCar;
    	
        return $this->render('group-parts',
        	[
        		'model' => $model,
        		'frame' => $frame,
        		'complectation' => $complectation,
        		'groupParts' => $complectation->getPartGroups()->where(['type' => $type])->all(),
        		'type' => $type,
        	]
        );
    }

    public function actionParts($id)
    {
    	$partsGroup = PartsGroups::findOne($id);
    	$complectation = $partsGroup->complectation;
    	$frame = $complectation->frame;
    	$model = $frame->modelCar;


    	
        return $this->render('parts',
        	[
        		'model' => $model,
        		'frame' => $frame,
        		'complectation' => $complectation,
        		'groupPart' => $partsGroup,
        		'parts' => $partsGroup->getParts()->where(['not like','pnc','SUBS'])->all(),
        		'type' => $partsGroup->type,
        	]
        );
    }

}
