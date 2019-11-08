<?php
/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */

namespace frame;

class route
{
    public $controller;
    public $action;

    /**
     *  路由解析
     */
    public function __construct()
    {

        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $path    = trim($_SERVER['REQUEST_URI'], '/');
            $get_arg = strstr($path, '?', true);
            $get_arg == false or $path = $get_arg;

            strpos($path, '/') or $path .= '/index';
            $route            = explode('/', $path);
            $this->controller = ucfirst($route[0]);
            $this->action     = $route[1] ?? 'index';

        } else {
            $this->controller = 'Index';
            $this->action     = 'index';
        }

    }

}