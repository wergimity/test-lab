<?php

use App\Storage\Fopen;
use App\Structure\SearchTree;

class SearchTreeTest extends PHPUnit_Framework_TestCase
{
    /** @var  SearchTree */
    protected $object;

    public function setUp()
    {
        $storage = new Fopen('fopen_test', 'w+');

        $this->object = new SearchTree($storage);
    }

    public function test_insertion_and_search()
    {
        $check = 'TestingValue';

        $this->object->insert('test', $check);

        $result = $this->object->search('test');

        $this->assertSame($check, $result);
    }
}
