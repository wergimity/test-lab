<?php
use App\Crawler\Glob;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-12-16 at 20:09:49.
 */
class GlobCrawlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Glob
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Glob('app/*');
    }

    /**
     * @covers App\Crawler\Glob::flags
     */
    public function testFlags()
    {
        $result = false;

        $step = function($file) use (&$result) {
            if($file == 'Crawler.php') $result = true;
        };

        $this->object->walk($step);

        $this->assertTrue($result);

        $result = false;

        $this->object->flags(GLOB_ONLYDIR);

        $this->object->walk($step);

        $this->assertFalse($result);
    }

    /**
     * @covers App\Crawler\Glob::walk
     */
    public function testWalk()
    {
        $count = 0;

        $this->object->walk(function($file) use (&$count) {

            $this->assertFileExists('app/' . $file);

            $count++;

        });

        $this->assertTrue($count > 0);
    }
}
