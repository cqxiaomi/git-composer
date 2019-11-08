<?php

/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */
namespace frame;

class app
{
    public static $classMap = [];

    /**
     *  自动实例化
     */
    static public function run()
    {

        $route  = new route();
        $action = $route->action;

        if (in_array($route->controller, ['base', 'token', 'api'])) {
            throw new \Exception('该控制器限制访问');
        }
        $controller = 'app/controllers/' . $route->controller . '.php';
        !strpos($controller, '\\') or $controller = str_replace('\\', '/', $controller);
        $controllerClass = '\\' . MODULE . '\\controllers\\' . $route->controller;

        if (is_file($controller)) {
            include $controller;
            $ctr = new $controllerClass();
            if (!in_array($action, get_class_methods($ctr))) {
                throw new \Exception('该方法不存在：' . $action . '()   ' . $controller);
            }
            $ctr->$action();
        } else {
            throw new \Exception('该控制器不存在：' . $controllerClass);
        }

    }

    /**
     *  自动加载 需要的文件  include $file;
     *
     * @param string $class
     * @return boolean
     */
    static public function load($class)
    {
        if (isset($classMap[$class])) {
            return true;
        } else {
            $newClass = str_replace('\\', '/', $class);
            $file     = PROJECT . '/' . $newClass . '.php';
            if (is_file($file)) {
                include $file;
                self::$classMap[$class] = $newClass;
                return true;
            } else {
                return false;
            }
        }

    }
}