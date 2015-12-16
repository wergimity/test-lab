<?php
namespace App;

interface Crawler
{
    /**
     * @param callable $step
     *
     * @return void
     */
    public function walk(callable $step);
}
