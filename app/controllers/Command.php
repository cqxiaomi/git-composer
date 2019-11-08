<?php
/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */

namespace app\controllers;
use app\lib\{Composer, Git, Jwt};

class Command extends Base
{
    protected $name;

    public function __construct()
    {
        parent::__construct();
        $token = isset($_SERVER['HTTP_TOKEN']) ? trim($_SERVER['HTTP_TOKEN']) : '';
        if (empty($token)) {
            $this->writeJson(TOKEN_ERROR_CODE, '请重新登录！');
        } else {
            try {
                $key     = $this->config('TOKEN.key');
                $decoded = Jwt::verifyToken($token, $key);
                if (!$decoded) {
                    $this->writeJson(TOKEN_ERROR_CODE, 'token error');
                }
                $this->name = isset($decoded['data']['name ']) ? $decoded['data']['name '] : '';
            } catch (\Exception $e) {
                if (DEV) {
                    var_dump($e->getMessage());
                    echo PHP_EOL;
                    var_dump($e->getFile());
                    echo PHP_EOL;
                    var_dump($e->getLine());
                }


                $this->writeJson(TOKEN_ERROR_CODE, '请重新登录！');
            }
        }
    }

    /**
     * project list
     */
    public function list()
    {
        $projects = $this->config('PROJECTS');

        empty($projects) or $projects = array_keys($projects);

        $this->writeJson(SUCCESSFUL_CODE, 'ok', $projects);
    }

    /**
     * composer
     */
    public function composer()
    {
        if (!isset($this->params['name']) || empty($this->params['name'])) {
            $this->writeJson(ERROR_CODE, '项目名称不能为空');
        }
        if (!isset($this->params['action']) || empty($this->params['action'])) {
            $this->writeJson(ERROR_CODE, '操作名称不能为空');
        }

        $name        = trim($this->params['name']);
        $action      = trim($this->params['action']);
        $packageName = isset($this->params['package_name']) ? trim($this->params['package_name']) : '';
        $projects    = $this->config('PROJECTS');
        if (!isset($projects[$name])) {
            $this->writeJson(ERROR_CODE, '项目配置有误');
        }
        if (in_array($action, ['require', 'remove']) && empty($packageName)) {
            $this->writeJson(ERROR_CODE, '包名称不能为空');
        }
        if ($action == 'update' && !is_file($projects[$name] . '/composer.json')) {
            $this->writeJson(ERROR_CODE, 'composer.json文件不存在');
        }

        try {
            $bin  = $this->config('COMPOSER.bin');
            $repo = Composer::open($projects[$name], $bin);
            $info = '';
            switch ($action) {
                case 'require':
                    $info = $repo->require($packageName);
                    break;
                case 'remove':
                    $info = $repo->remove($packageName);
                    break;
                case 'update':
                    $info = $repo->update();
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {

            $this->writeJson(ERROR_CODE, preg_replace('#   #', '', $e->getMessage()));
        }

        $this->writeJson(SUCCESSFUL_CODE, 'ok', ['info' => $info]);

    }


    /**
     * git
     */
    public function git()
    {
        if (!isset($this->params['name']) || empty($this->params['name'])) {
            $this->writeJson(ERROR_CODE, '项目名称不能为空');
        }

        $name     = trim($this->params['name']);
        $projects = $this->config('PROJECTS');
        if (!isset($projects[$name])) {
            $this->writeJson(ERROR_CODE, '项目配置有误');
        }

        try {
            $repo = Git::open($projects[$name]);
            $info = $repo->pull();
        } catch (\Exception $e) {
            $this->writeJson(ERROR_CODE, preg_replace('#   #', '', $e->getMessage()));
        }

        $this->writeJson(SUCCESSFUL_CODE, 'ok', ['info' => $info]);


    }

    /**
     * command
     */
    public function command()
    {
        if (!isset($this->params['name']) || empty($this->params['name'])) {
            $this->writeJson(ERROR_CODE, '项目名称不能为空');
        }
        if (!isset($this->params['action']) || empty($this->params['action'])) {
            $this->writeJson(ERROR_CODE, '操作名称不能为空');
        }

        $name     = trim($this->params['name']);
        $action   = trim($this->params['action']);
        $projects = $this->config('PROJECTS');
        if (!isset($projects[$name])) {
            $this->writeJson(ERROR_CODE, '项目配置有误');
        }
        $file = $projects[$name] . '/composer.json';
        if ($action == 'cat' && !is_file($file)) {
            $this->writeJson(ERROR_CODE, 'composer.json文件不存在');
        }

        try {
            $info = '';
            switch ($action) {
                case 'cat':
                    $info = file_get_contents($file);
                    break;
                default:
                    break;
            }

        } catch (\Exception $e) {
            $this->writeJson(ERROR_CODE, preg_replace('#   #', '', $e->getMessage()));
        }

        $this->writeJson(SUCCESSFUL_CODE, 'ok', ['info' => $info]);


    }
}