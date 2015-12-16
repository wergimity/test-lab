<?php

namespace App\Structure;

use App\Storage;
use App\Structure;

class SearchTree implements Structure
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
     * @var int
     */
    private $addressLength;

    /**
     * @var int
     */
    private $fullLength;

    /**
     * @var int
     */
    private $nextAddress;

    /**
     * @var int
     */
    private $top;

    public function __construct(Storage $storage, $size = 100, $dataLength = 30)
    {
        $this->storage = $storage;
        $this->size = $size;
        $this->dataLength = $dataLength;
        $this->nextAddress = 0;
        $this->top = 0;
        $this->addressLength = ceil(log10($this->size));
        $this->fullLength = $this->dataLength * 2 + $this->addressLength * 3;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function insert($key, $value)
    {
        $this->find($key, $parent);

        $this->store($key, $value, $parent);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function search($key)
    {
        $address = $this->find($key);

        if ($address === null) {
            return;
        }

        $node = $this->node($address);

        return $this->value($node);
    }

    private function find($key, &$parent = null)
    {
        $current = $this->top;

        $parent = -1;

        do {
            $node = $this->node($current);

            $nodeKey = $this->key($node);

            $leftNode = $this->left($node);

            $rightNode = $this->right($node);

            if (!$nodeKey || $nodeKey == $key) {
                break;
            }

            $parent = $current;

            if ($nodeKey > $key) {
                $current = $leftNode;
            } else {
                $current = $rightNode;
            }
        } while (trim($node) && $current !== null);

        if (!trim($node)) {
            $parent = null;

            return;
        }

        return (int) $current;
    }

    /**
     * @param int $address
     *
     * @return string
     */
    private function node($address)
    {
        return $this->storage->read($this->fullLength, $address * $this->fullLength);
    }

    /**
     * @param string $node
     *
     * @return string
     */
    private function key($node)
    {
        return trim(substr($node, 0, $this->dataLength));
    }

    /**
     * @param string $node
     *
     * @return string
     */
    private function value($node)
    {
        return trim(substr($node, $this->dataLength, $this->dataLength));
    }

    private function left($node)
    {
        $value = substr($node, $this->dataLength * 2 + $this->addressLength);

        if ('' == trim($value)) {
            return;
        }

        if (-1 == (int) $value) {
            return;
        }

        return (int) $value;
    }

    private function right($node)
    {
        $value = substr($node, $this->dataLength * 2 + $this->addressLength * 2);

        if ('' == trim($value)) {
            return;
        }

        if (-1 == (int) $value) {
            return;
        }

        return (int) $value;
    }

    /**
     * @param string $key
     * @param string $value
     * @param int    $parent
     *
     * @return int
     */
    private function store($key, $value, $parent = -1)
    {
        $address = $this->nextAddress++;

        $node = $this->make($key, $value, $parent);

        $this->storage->write($node, $address * $this->fullLength);

        if ($parent < 0) {
            $this->top = $address;
        }

        return $address;
    }

    private function make($key, $value, $parent = -1, $left = null, $right = null)
    {
        $node = $this->fix($key, $this->dataLength);

        $node .= $this->fix($value, $this->dataLength);
        $node .= $this->fix($parent, $this->addressLength);
        $node .= $this->fix($left, $this->addressLength);
        $node .= $this->fix($right, $this->addressLength);

        return $node;
    }

    /**
     * @param string $value
     * @param int    $length
     *
     * @return string
     */
    private function fix($value, $length)
    {
        return str_pad(substr($value, 0, $length), $length, ' ');
    }
}
