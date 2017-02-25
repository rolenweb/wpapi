<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Console;

use Rolenweb\Wpapi\Wp;
use app\models\geo\City;
use app\models\Domain;
use app\models\Position;

/**
 * This command to create new terms from array.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NewMapController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        $cache->flush();
        $this->success('Start: cache is cleared');
        for ($n=0;$n<10;$n++) { 
            $position = Position::findOne(['status' => 2]);
            if (empty($position)) {
                $this->error('The position is not found');
                return;
            }

            $city = $position->objectModel;
            if (empty($city)) {
                $this->error('The city is not found');
                return;
            }
            $state = $city->state;
            if (empty($state)) {
                $this->error('State is not found');
                continue;
            }
            $this->whisper($city->name.', '.$state->code);

            $domain = $position->domain;
            if (empty($domain)) {
                $this->error('The domain is not found');
                return;
            }

            $categories = $cache->get('domain_'.$domain->id);
            if ($categories === false) {
                $categories = $this->categories($domain);    
                $cache->set('domain_'.$domain->id,$categories);
            }
            
            $categoryId = array_search(strtolower($city->state->code), $categories);

            if (empty($categoryId)) {
                $this->error('Category is not found');
                return;
            }
            
            $map = $this->newMap($city,$domain);
            if (empty($map['error']) === false) {
                if (empty($result['error']['code']) === false) {
                    $this->error('Code: '.$result['error']['code']);
                }
                if (empty($result['error']['description']) === false) {
                    $this->error('Description: '.$result['error']['description']);
                }
            }
            
            if (empty($map['data'])) {
                $this->error('Response is null');
                $position->next;
                continue;
            }
            $this->whisper('Map is created');

            $contentPage = $this->contentPage($map['data'],$city);
            if (empty($contentPage)) {
                $this->error('Content is null');
                continue;
            }
            $this->whisper('Content is ready');

            $page = $this->newPage($city,$domain,$contentPage,$categoryId);
            if (empty($page['error']) === false) {
                if (empty($result['error']['code']) === false) {
                    $this->error('Code: '.$result['error']['code']);
                }
                if (empty($result['error']['description']) === false) {
                    $this->error('Description: '.$result['error']['description']);
                }
            }else{
                $this->success('Page is created');
            }
            $position->next;
        }
    }

    public function newMap($city,$domain)
    {
        $result = [];
        $wp = new Wp($domain->domain,$domain->login,$domain->pass);
        
        $content = [
            'post_type' => 'gmaps_map_ve',
            'post_content' => null,
            'post_title' => $city->id,
            'post_status' => 'publish',
            'ping_status' => 'closed',
            'custom_fields' => [
                [
                    'key' => 've_map_control_pan',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_control_zoom',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_control_map_type',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_control_scale',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_control_street_view',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_control_overview',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_layer_bicycling',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_layer_traffic',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_layer_transit',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_long',
                    'value' => $city->lon
                ],
                [
                    'key' => 've_map_lat',
                    'value' => $city->lat
                ],
                [
                    'key' => 've_map_client_based_locatio',
                    'value' => 'no'
                ],
                [
                    'key' => 've_map_zoom',
                    'value' => '12'
                ],
                [
                    'key' => 've_map_height_type',
                    'value' => 'px'
                ],
                [
                    'key' => 've_map_height',
                    'value' => '400'
                ],
                [
                    'key' => 've_map_width_type',
                    'value' => '%'
                ],
                [
                    'key' => 've_map_width',
                    'value' => '100'
                ],
                [
                    'key' => 've_map_type',
                    'value' => 'ROADMAP'
                ],
            ],
        ];

        $result = $wp->newPost($content);
        if (empty($result['data']) === false) {
            $mapId = (int)$result['data'];
            $companies = $city->companies;
            $total = count($companies);
            $i = 0;
            Console::startProgress($i, $total); 
            foreach ($companies as $company) {
                $contentMarker = [
                    'post_type' => 'gmaps_marker_ve',
                    'post_content' => $company->address,
                    'post_title' => $company->title,
                    'post_status' => 'publish',
                    'ping_status' => 'closed',
                    'post_parent' => $mapId,
                    'custom_fields' => [
                        [
                            'key' => 'marker_animation',
                            'value' => 'none'
                        ],
                        [
                            'key' => 'marker_long',
                            'value' => (empty($company->geo) === false) ? $company->geo->lon : null
                        ],
                        [
                            'key' => 'marker_lat',
                            'value' => (empty($company->geo) === false) ? $company->geo->lat : null
                        ],
                        [
                            'key' => 'marker_description',
                            'value' => null,
                        ],
                        [
                            'key' => 'marker_address',
                            'value' => null
                        ],
                    ],
                ];
                $wp->newPost($contentMarker);   
                Console::updateProgress(++$i, $total);
            }
            Console::endProgress();
        }
        return $result;
    }

    public function contentPage($mapId,$city)
    {
        $companies = $city->companies;
        if (empty($companies)) {
            return;
        }
        $content = Html::tag('div','[ve_gmap map="'.$mapId.'"]',['class' => 'map']);
        $content .= Html::beginTag('div',['class' => 'table-responsive']);
        $content .= Html::beginTag('table',['class' => 'table']);
        $content .= Html::beginTag('tbody');
        foreach ($companies as $company) {
            $content .= Html::tag('tr',
                Html::tag('td',$company->title).
                Html::tag('td',Html::tag('ul',Html::tag('li',$company->address).Html::tag('li',$company->phone).Html::tag('li',$company->website),['class' => 'address'])).
                Html::tag('td',$company->category)
                );    
        }
        $content .= Html::endTag('tbody');
        $content .= Html::endTag('table');
        $content .= Html::endTag('div');
        return $content;
    }

    public function categories($domain)
    {
        $result = [];
        $wp = new Wp($domain->domain,$domain->login,$domain->pass);
        $terms = $wp->getTerms('category');
        
        return ArrayHelper::map($terms['data'],'item.1.name','item.2.slug');
        
    }

    public function newPage($city,$domain,$contentPage,$terms)
    {
        $wp = new Wp($domain->domain,$domain->login,$domain->pass);
        
        $content = [
            'post_type' => 'post',
            'post_content' => $contentPage,
            'post_title' => 'Pizza near '.$city->name.', '.$city->state->code,
            'post_status' => 'publish',
            'ping_status' => 'open',
            'terms_names' => [
                'category' => [$terms],
            ],
        ];

        return $wp->newPost($content);
    }
}
