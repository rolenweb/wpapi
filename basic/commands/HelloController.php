<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

use Rolenweb\Wpapi\Wp;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $wp = new Wp('http://wordpress1/','rolenweb','gfhjkm21');
        

        $content = [
        	'name' => 'tag10',
            'taxonomy' => 'post_tag',
            //'slug' => null,
            //'description' => null,
            //'parent' => null,
        ];
        //var_dump($wp->newTerm($content));
        //
        var_dump($wp->getTerms('post_tag'));
        
    }
}
