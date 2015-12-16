<?php
namespace App\Crawler;

use App\Crawler;

class OpenDir implements Crawler
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $recursive;

    /**
     * @param string $path
     * @param bool   $recursive
     */
    public function __construct($path, $recursive = false)
    {
        $this->path      = $path;
        $this->recursive = $recursive;
    }

    /**
     * @param callable $step
     *
     * @return void
     */
    public function walk(callable $step)
    {
        $this->iterate($step, $this->path);
    }

    /**
     * @param callable $step
     * @param string   $path
     */
    private function iterate(callable $step, $path)
    {
        if (!is_readable($path)) {
            return;
        }

        $directory = opendir($path);

        while (false !== ($file = readdir($directory))) {
            $this->step($step, $path, $file);
        }

        closedir($directory);
    }

    /**
     * @param callable $step
     * @param string   $path
     * @param string   $file
     */
    private function step(callable $step, $path, $file)
    {
        if ($this->shouldExclude($file)) {
            return;
        }

        if ($this->shouldIterate($this->join($path, $file))) {
            $this->iterate($step, $this->join($path, $file));
        }

        $step($file);
    }

    /**
     * @param string $path
     * @param string $file
     *
     * @return string
     */
    private function join($path, $file)
    {
        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    private function shouldExclude($file)
    {
        return $file == '.' || $file == '..';
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private function shouldIterate($path)
    {
        return $this->recursive && is_dir($path);
    }
}
