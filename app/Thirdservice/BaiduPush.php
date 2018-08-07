<?php
/**
 * Created by PhpStorm.
 * User: ly
 * Date: 2018/8/1
 * Time: 9:41
 */

namespace App\Thirdservice;


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
     */
    public function pushUrl ()
    {
        //记录请求日志
        recordLog('baiduPush推送',storage_path('logs/baiduPush.log'), json_encode($this->options));

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $this->result = json_decode(curl_exec($ch));

        //记录请求结果日志
        recordLog('baiduPush结果',storage_path('logs/baiduPush.log'), json_encode($this->result));
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