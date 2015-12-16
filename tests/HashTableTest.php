<?php

use App\Structure\HashTable;

class HashTableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HashTable
     */
    protected $object;

    public function setUp()
    {
        $storage = new \App\Storage\Fopen('fopen_test', 'w+');

        $this->object = new HashTable($storage);
    }

    public function test_insertion_and_search()
    {
        $check = 'TestingValue';

        $this->object->insert('test', $check);

        $result = $this->object->search('test');

        $this->assertSame($check, $result);
    }
}
