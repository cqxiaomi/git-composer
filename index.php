<?php
/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */

define('DEV', true);
define('MODULE', 'app'); // 模块文件名
define('PROJECT', __DIR__); // 项目根目录
define('SUCCESSFUL_CODE', 1);// 成功
define('FAILED_CODE', -1);// 未知失败
define('ERROR_CODE', -2);// 错误
define('TOKEN_ERROR_CODE', -20);// token


!DEV or error_reporting(E_ALL);
include PROJECT . '/frame/app.php';
spl_autoload_register('\frame\app::load');
try {
    \frame\app::run();
} catch (\Exception $e) {
    if (DEV) {
        var_dump($e->getMessage());
        echo PHP_EOL;
        var_dump($e->getFile());
        echo PHP_EOL;
        var_dump($e->getLine());
    }
}