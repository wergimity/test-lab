<?php
namespace App\Storage;

use App\Storage;
use Exception;

class Fopen implements Storage
{
    /**
     * @var resource
     */
    private $handle;

    /**
     * @param $filename
     */
    public function __construct($filename, $mode)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new Exception("File $filename does not exist or is not readable");
        }

        $this->handle = fopen($filename, $mode);
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    /**
     * @param int      $length
     * @param int|null $position
     *
     * @return string
     */
    public function read($length, $position = null)
    {
        if (null !== $position) {
            fseek($this->handle, $position);
        }

        return fread($this->handle, $length);
    }

    /**
     * @param string   $data
     * @param int|null $position
     *
     * @return void
     */
    public function write($data, $position = null)
    {
        if (null !== $position) {
            fseek($this->handle, $position);
        }

        fwrite($this->handle, $data);
    }

    /**
     * @param int $position
     *
     * @return void
     */
    public function move($position)
    {
        fseek($this->handle, $position);
    }

    /**
     * @return bool
     */
    public function ends()
    {
        return feof($this->handle);
    }

    /**
     * @return resource
     */
    public function handle()
    {
        return $this->handle;
    }
}
