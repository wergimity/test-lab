<?php
namespace App;

interface Storage
{
    /**
     * @param int      $length
     * @param int|null $position
     *
     * @return string
     */
    public function read($length, $position = null);

    /**
     * @param string   $data
     * @param int|null $position
     *
     * @return void
     */
    public function write($data, $position = null);

    /**
     * @param int $position
     *
     * @return void
     */
    public function move($position);

    /**
     * @return bool
     */
    public function ends();
}
