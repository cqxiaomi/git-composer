<?php

namespace app\configs;

class Produce
{
    public static $data = [
        /*################ TOKEN CONFIG ##################*/
        'COMPOSER' => [
            'bin' => '/usr/bin/composer' // whereis composer
        ],
        /*################ TOKEN CONFIG ##################*/
        'TOKEN'    => [
            'key'  =>
                'xv3INQJ8WhDdgHvGAH6x'
            ,
            //        'iss'  => 'http://www.xxx.com',//签发者
            'iat'  => 0,
            //        'nbf'  => 0,
            'exp'  => 36000,
            'data' => []
        ],
        /*################ USERS CONFIG ##################*/
        'USERS'    => [
            'admin' => 'admin', // name/password,
        ]
        ,

        /*################ PROJECTS CONFIG ##################*/
        'PROJECTS' => [
            'shop'        => '/shop/www/staff.test.com', // name/path,
            'app'          => '/data/www/app.test.com',
            'back'         => '/data/www/back.test.com',
            'admin'        => '/data/www/admin.test.com',
            'provider'     => '/data/www/provider.test.com',
            'git-composer' => '/mnt/hgfs/demo/git-composer'
        ],
    ];

    /**
     *  获取配置
     * @param string $key
     * @return array|mixed|null
     */
    static public function get($key = '')
    {
        if (empty($key)) {
            return self::$data;
        }
        $keys = explode('.', $key);

        if (count($keys) == 2) {
            return isset(self::$data[$keys[0]][$keys[1]]) ? self::$data[$keys[0]][$keys[1]] : null;
        } else {
            return isset(self::$data[$key]) ? self::$data[$key] : null;
        }
    }
}