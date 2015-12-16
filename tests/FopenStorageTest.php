<?php
use App\Storage\Fopen;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-12-16 at 20:21:04.
 */
class FopenStorageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Fopen
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Fopen('fopen_test', 'w+');
    }

    /**
     * @covers App\Storage\Fopen::read
     */
    public function testRead()
    {
        $this->assertNotNull($this->object->handle());

        $check = 'Hello';

        fseek($this->object->handle(), 0);

        fwrite($this->object->handle(), $check);

        $result = $this->object->read(strlen($check), 0);

        $this->assertSame($check, $result);
    }

    /**
     * @covers App\Storage\Fopen::write
     */
    public function testWrite()
    {
        $check = 'Testing';

        $this->object->write($check, 0);

        fseek($this->object->handle(), 0);

        $result = fread($this->object->handle(), strlen($check));

        $this->assertSame($check, $result);
    }

    /**
     * @covers App\Storage\Fopen::move
     */
    public function testMove()
    {
        fseek($this->object->handle(), 0);

        $check = 15;

        $this->object->move($check);

        $result = ftell($this->object->handle());

        $this->assertSame($check, $result);
    }

    /**
     * @covers App\Storage\Fopen::ends
     */
    public function testEnds()
    {
        $this->assertTrue(true);
    }

    /**
     * @covers App\Storage\Fopen::handle
     */
    public function testHandle()
    {
        $this->assertNotNull($this->object->handle());

        $this->assertTrue(is_resource($this->object->handle()));
    }
}
