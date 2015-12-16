<?php
namespace App\Crawler;

use App\Crawler;

class Glob implements Crawler
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var int
     */
    private $flags;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param int $flags
     *
     * @return void
     */
    public function flags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * @param callable $step
     *
     * @return void
     */
    public function walk(callable $step)
    {
        $files = glob($this->pattern, $this->flags);

        foreach ($files as $file) {
            $step(basename($file));
        }
    }
}
