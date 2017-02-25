<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\commands\tools\CurlClient;
use SimpleXMLElement;

/**
 * ContactForm is the model behind the contact form.
 */
class Wp extends Model
{
    public $url;
    public $login;
    public $pass;
    
    public function __construct($url = null, $login = null, $pass = null)
    {
        $this->url = $url;   
        $this->login = $login;   
        $this->pass = $pass;   
    }

    public function rules()
    {
        return [
            [['url','login','pass'], 'string'],
            [['url','login','pass'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => 'Url',
            'login' => 'Login',
            'pass' => 'Password',
            
        ];
    }

    public function newTerm($content)
    {   
        $params = array(1, $this->login, $this->pass, $content);
        
        $response = $this->xmlRpc($params,'wp.newTerm');

        return $this->convertResponseArray($response);
    }

    public function getTerms($taxonomy,$filter = [])
    {
        $params = array(1, $this->login, $this->pass, $taxonomy, $filter);
        
        $response = $this->xmlRpc($params,'wp.getTerms');

        //var_dump($response);

        return $this->convertResponseArray($response);
    }

    public function xmlRpc($params,$action)
    {
        // initialize curl
        $ch = curl_init();
        // set url ie path to xmlrpc.php
        curl_setopt($ch, CURLOPT_URL, $this->url.'/xmlrpc.php');
        // xmlrpc only supports post requests
        curl_setopt($ch, CURLOPT_POST, true);
        // return transfear
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // setup post data
        
        $params = xmlrpc_encode_request($action, $params);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        // execute the request
        $response = curl_exec($ch);
        // shutdown curl
        curl_close($ch);
        return $response;
    }

    public function convertResponseArray($xml)
    {
        $out = [];
        $obj = new SimpleXMLElement($xml);
        $array = $this->extract($obj);
        
        if (empty($array['fault']) === false) {
            if (empty($array['fault']['value']) === false) {
                if (empty($array['fault']['value']['struct']) === false) {
                    if (empty($array['fault']['value']['struct']['member']) === false) {
                        foreach ($array['fault']['value']['struct']['member'] as $member) {
                            switch ($member['name']) {
                                case 'faultCode':
                                    $out['error']['code'] = $member['value']['int'];   
                                    break;

                                case 'faultString':
                                    $out['error']['description'] = $member['value']['string'];   
                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                        }            
                    } 
                }
            }
        }else{
            if (empty($array['params']) === false) {
                if (empty($array['params']['param']) === false) {
                    if (empty($array['params']['param']['value']['string']) === false) {
                        $out['data'] = $array['params']['param']['value']['string'];
                    }

                    if (empty($array['params']['param']['value']['array']['data']) === false) {
                        foreach ($array['params']['param']['value']['array']['data'] as $items) {
                            foreach ($items as $nItem => $item) {
                                if (empty($item['struct']['member']) === false) {
                                    foreach ($item['struct']['member'] as $nMember => $member) {
                                        $out['data'][$nItem]['item'][$nMember][$member['name']] = (empty($member['value']['string']) === false) ? $member['value']['string'] : null;
                                    }
                                }
                            }
                            
                        }
                    }
                    
                }
            }
        }
        
        return $out;
    }

    public function extract($sxe = null) {
        if (!$sxe instanceOf SimpleXMLElement)
            return array();

        $extract = array();

        foreach ($sxe->children() as $key => $value) {
            if (array_key_exists($key, $extract)) {
                if (!isset($extract[$key][0])) {
                    $tmp_extract = $extract[$key];
                    $extract[$key] = array();
                    $extract[$key][0] = $tmp_extract;
                } else
                    $extract[$key] = (array) $extract[$key];
            }

            if ($value->count()) {
                if (isset($extract[$key]) && is_array($extract[$key]))
                    $extract[$key][] = $this->extract($value);
                else
                    $extract[$key] = $this->extract($value);
            } else {
                if (isset($extract[$key]) && is_array($extract[$key]))
                    $extract[$key][] = empty(strval($value)) ? null : strval($value);
                else
                    $extract[$key] = empty(strval($value)) ? null : strval($value);
            }
        }

        return $extract;
    }
    
}
