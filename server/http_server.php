<?php
/**
 * Created by PhpStorm.
 * User: liyu
 * Date: 2019/3/6
 * Time: 11:38
 */

$http = new swoole_http_server('127.0.0.1', 1215);

$http->set([
    'worker_num' => 8,
    'max_request' => 5000,
]);

//工作进程启动
$http->on('WorkerStart', function ($server, $worker_id) {
    require __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../bootstrap/app.php';
});

//监听Http请求
$http->on('request', function ($request, $response) {

    //server信息
    if (isset($request->server)) {
        foreach ($request->server as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    //header信息
    if (isset($request->header)) {
        foreach ($request->header as $k => $v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    //get请求

    if ($request->get) {
        foreach ($request->get as $k => $v) {
            $_GET[$k] = $v;
        }
    }

    //post请求
    if ($request->post) {
        foreach ($request->post as $k => $v) {
            $_POST[$k] = $v;
        }
    }

    //文件请求
    if ($request->files) {
        foreach ($request->files as $k => $v) {
            $_FILES[$k] = $v;
        }
    }

    //cookie请求
    if ($request->cookie) {
        foreach ($request->cookie as $k => $v) {
            $_COOKIE[$k] = $v;
        }
    }

    ob_start();//启用缓冲区

    //加载Laravel请求核心模块
    $kernel = app()->make(Illuminate\Contracts\Http\Kernel::class);
    $laravelResponse = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    $laravelResponse->send();
    $kernel->terminate($request, $laravelResponse);

    $res = ob_get_contents();   //获取缓冲区的内容
    ob_end_clean(); //清除缓冲区

    $response->end($res);
});

$http->start();