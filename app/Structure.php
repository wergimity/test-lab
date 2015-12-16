<?php
namespace App;

interface Structure
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function insert($key, $value);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function search($key);
}
