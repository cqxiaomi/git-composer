<?php

/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */
namespace app\controllers;
use app\lib\Jwt;

class Open extends Base
{

    /**
     * 登录
     */
    public function login()
    {

        if (!isset($this->params['name']) || empty($this->params['name'])) {
            return $this->writeJson(ERROR_CODE, '账号不能为空');
        }
        if (!isset($this->params['password']) || empty($this->params['password'])) {
            $this->writeJson(ERROR_CODE, '密码不能为空');
        }

        $users = $this->config('USERS');
        if (!in_array($this->params['name'], array_keys($users)) || $this->params['password'] != $users[$this->params['name']]) {
            $this->writeJson(ERROR_CODE, '用户不存在或者密码输入不正确!');
        }

        $data         = $this->config('TOKEN');
        $data['iat']  = time();
        $data['exp']  = time() + $data['exp'];
        $data['jti']  = md5(uniqid('JWT') . time());
        $data['data'] = ['name' => $this->params['name']];
        $key          = $data['key'];
        unset($data['key']);
        $token = Jwt::getToken($data, $key);


        $this->writeJson(SUCCESSFUL_CODE, '登录成功', ['token' => $token]);


    }


}