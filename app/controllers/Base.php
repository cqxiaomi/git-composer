<?php
/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */

namespace app\controllers;
use app\configs\Produce;

class Base
{
    protected $params = [];

    public function __construct()
    {
        $this->params = array_merge($_GET, $_POST);
    }

    protected function display($name = 'index')
    {
        $file = PROJECT . '/app/views/' . $name . '.html';
        if (is_file($file)) {
            include $file;
        }
    }

    protected function writeJson($statusCode = 200, $msg = null, $data = [], $count = null)
    {
        $data = Array(
            "code" => $statusCode,
            "msg"  => $msg,
            "data" => $data
        );
        is_null($count) or $data['count'] = $count;
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;

    }

    protected function config($key = '')
    {
        return Produce::get($key);
    }

}