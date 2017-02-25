<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

use yii\helpers\ArrayHelper;
use yii\helpers\Console;

use Rolenweb\Wpapi\Wp;
use app\models\Domain;
use app\models\geo\State;

/**
 * This command to create new terms from array.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NewTermController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $states = State::find()->all();

        $data = ArrayHelper::toArray($states, [
            'app\models\geo\State' => [
                'name' => function ($state) {
                    return $state->name;
                },
                'taxonomy' => function ($state) {
                    return 'category';
                },
                'slug' => function ($state) {
                    return strtolower($state->code);
                },
            ],
        ]);

        if (empty($data)) {
            $this->error('The data is null');
            return;
        }

        $results = $this->new($data);

        if (empty($results) === false) {
            foreach ($results as $name => $result) {
                if (empty($result['error']) === false) {
                    $this->error($name);
                    if (empty($result['error']['code']) === false) {
                        $this->error('Code: '.$result['error']['code']);
                    }
                    if (empty($result['error']['description']) === false) {
                        $this->error('Description: '.$result['error']['description']);
                    }
                }else{
                    $this->success($name);
                }
                
            }
        }

    }

    /**
     * [new description]
     * @param  [type] $array 
     * @return [type]        [description]
     */
    public function new($data)
    {
        $result = [];
        $domain = Domain::find()->where(['status' => 2])->limit(1)->one();
        if (empty($domain)) {
            $this->error('The domain is not found');
            return;
        }
        $wp = new Wp($domain->domain,$domain->login,$domain->pass); 
        $total = count($data);
        $i = 0;
        Console::startProgress($i, $total);  
        foreach ($data as $item) {
            $result[$item['name']] = $wp->newTerm($item);
            Console::updateProgress(++$i, $total);
        }
        Console::endProgress();
        return $result;
    }
}
