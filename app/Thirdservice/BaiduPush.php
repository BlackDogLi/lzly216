<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2018/8/1
 * Time: 9:41
 */

namespace App\Thirdservice;

use Illuminate\Support\Facades\Log;
class BaiduPush
{
    private $api = 'http://data.zz.baidu.com/urls?site=www.lzly216.com&token=reQlpWpQpQcY5z3D';
    private $url;
    private $options = array();
    protected $result;

    /**
     * BaiduPush constructor.
     * @param $url
     */
    public function __construct ($url)
    {
        $this->url = is_array($url)? $url : (array)$url;
        self::setOptions();
    }

    /**
     * 推送链接
     * TODO:记录请求日志
     */
    public function pushUrl ()
    {
        //(new \Log(base_path().'/logs/Baidu.log'))->info('发起推送',$this->options);

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $this->result = json_decode(curl_exec($ch));
        //Log::useFiles(base_path().'/logs/Baidu.log')->info('发起推送',$this->result);
        return $this->result;
    }

    /**
     * 设置参数
     */
    private function setOptions ()
    {
       $this->options = array(
           CURLOPT_URL              => $this->api,
           CURLOPT_POST             => true,
           CURLOPT_RETURNTRANSFER   => true,
           CURLOPT_POSTFIELDS       => implode("\n", $this->url),
           CURLOPT_HTTPHEADER       => array('Content-Type: text/plain'),
       );
    }

}