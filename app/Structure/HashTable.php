<?php
namespace App\Structure;

use App\Storage;
use App\Structure;

class HashTable implements Structure
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $dataLength;

    /**
     * @param Storage $storage
     * @param int     $size
     * @param int     $dataLength
     */
    public function __construct(Storage $storage, $size = 100, $dataLength = 30)
    {
        $this->storage = $storage;
        $this->size    = $size;
        $this->dataLength = $dataLength;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function insert($key, $value)
    {
        $this->fixLength($key, $value);

        $hash = $this->hash($key);

        $key = str_pad($key, $this->dataLength, ' ');

        $value = str_pad($value, $this->dataLength, ' ');

        $this->storage->write($key . $value, $this->position($hash));
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function search($key)
    {
        $hash = $this->hash(trim($key));

        $value = $this->storage->read($this->dataLength, $this->position($hash) + $this->dataLength);

        $value = trim($value);

        if ('' == $value) {
            return null;
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return int
     */
    private function hash($key)
    {
        $result = 0;

        for ($i = 0; $i < 3; $i++) {
            $char = @ord($key[$i]);

            $result += $char * pow(256, $i);
        }

        return $result % $this->size;
    }

    private function fixLength(&$key, &$value)
    {
        $key = substr(trim($key), 0, $this->dataLength);

        $value = substr(trim($value), 0, $this->dataLength);
    }

    private function position($hash)
    {
        return ($hash % $this->size) * $this->dataLength * 2;
    }
}
