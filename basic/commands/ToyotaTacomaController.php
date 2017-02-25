<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Console;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

use app\models\Domain;
use app\models\Position;
use app\models\toyota\tacoma\Parts;

/**
 * This command to create new terms from array.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ToyotaTacomaController extends BaseCommand
{

	protected $url;
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {

    	$domain = Domain::findOne(['status' => 2]);
    	if (empty($domain)) {
    		$this->error('The domain is not found');
    		return;
    	}

    	$this->url = $domain->domain;

    	$woocommerce = new Client(
		    $domain->domain, 
		    $domain->login, 
		    $domain->pass,
		    [
		        'wp_api' => true,
		        'version' => 'wc/v1',
		    ]
		);

    	for ($i = 0;$i < 10000; $i++) { 
        	$position = Position::findOne(['status' => 2,'domain_id' => $domain->id]);
            if (empty($position)) {
                $this->error('The position is not found');
                return;
            }
            
            $part = $position->objectModel;
            if (empty($part)) {
                $this->error('The part is not found');
                return;
            }
            $structure = $part->structure;

            if (empty($structure)) {
            	$this->error('The structure is not found');
                return;
            }

            if (count($structure) !== 4) {
            	$this->error('The structure is failed: '.count($structure);
            	$position->next;
            	continue;	
            }

            $categoryId = $this->saveStructure($woocommerce,$structure);

            if (empty($categoryId)) {
            	$this->error('Categoty ID is not found');
            	return;
            }

            $partId = $this->savePart($woocommerce,$part,$categoryId);
            
            $position->next;
            //die;
        }
    }

    public function saveStructure($woocommerce,$structure)
    {
    	$categotyId = null;
    	foreach ($structure as $item) {
    		$this->whisper('Try to save '.$item['title'].' category');
    		$categotyId = $this->saveCategoty($woocommerce,$item,$categotyId);
    	}
    	return $categotyId;
    }

    public function saveCategoty($woocommerce,$item,$parentId)
    {
    	try{
			$out = $woocommerce->get('products/categories',[
				'search' => str_replace('&', '&amp;', $item['title']),//StringHelper::truncate(strtolower($item['title']),25),		
				'parent' => (empty($parentId) === false) ? $parentId : 0,
			]);
			//$savedCat = $this->searchCategory($out,$item['title']);
			if (empty($out[0]) === false) {
				$this->whisper('already saved');
				return $out[0]['id'];
			}
		}catch (HttpClientException $e) {
			$this->error($e->getMessage()); // Error message.
		    return;
		}

		try {
			$out = $woocommerce->post('products/categories', [
				'name' => strtolower($item['title']),
				//'description' => $item['description'],
				'parent' => $parentId,
			]);
			$this->whisper('saved');
			return $out['id'];
		}catch (HttpClientException $e) {
			$this->error($e->getMessage()); // Error message.
		    return;
		}
    }

    public function savePart($woocommerce,$part,$categoryId)
    {
    	$this->whisper('Try to save part: '.$part->name);
    	$this->whisper('Price: '.$part->clearPrice().' orifinal: '.$part->price);
    	try {
			$out = $woocommerce->post('products', [
				'name' => $part->name.' - '.$part->oem,
				'type' => 'external',
				'sku' => $part->oem.'-'.$part->group->complectation->complectation,
				'short_description' => $part->htmlShortDescription,
				'description' => $part->htmlDescription,
				'regular_price' =>  $part->clearPrice(),
				'external_url' => $this->url.'/goto/https://www.amayama.com/en/search/?q='.$part->oem,
				'button_text' => 'Order',
				'categories' => [
			        [
			            'id' => $categoryId
			        ],
			        
			    ],
			]);
			$this->whisper('saved');
			return $out['id'];
		}catch (HttpClientException $e) {
			$this->error($e->getMessage()); // Error message.
		    return;
		}
    }

    public function searchCategory($categories,$title)
    {
    	var_dump($categories);
    	die;
    }
    
}
