<?php
/**
 * Created by PHPSTORM.
 * User: cqxiaomi
 * Date: 2019/11/2
 * Time: 13:52
 */

namespace app\lib;

class Composer
{
    protected static $bin = ''; // whereis composer /usr/bin/composer

    private $path;

    public static function open($path, $composerBin = '')
    {
        if (!empty($composerBin)) {
            self::$bin = $composerBin;
        }
        return new static($path);
    }

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function update()
    {
        return $this->runCommand('update -vv');
    }

    public function require($packageName)
    {
        return $this->runCommand('require ' . $packageName);
    }

    public function remove($packageName)
    {
        return $this->runCommand('remove ' . $packageName);
    }

    public function runCommand($command)
    {
        $command        = static::$bin . ' ' . $command;
        $descriptorspec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $pipes          = [];
        $cwd            = $this->path;
        $resource       = proc_open($command, $descriptorspec, $pipes, $cwd);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        $status = trim(proc_close($resource));
        if ($status) throw new \Exception($stderr . "\n" . $stdout); //Not all errors are printed to stderr, so include std out as well.

        return $stdout ? $stdout : $stderr;
    }
}