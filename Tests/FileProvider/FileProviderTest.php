<?php

namespace Ticketpark\FileBundle\Tests\FileProvider;

use Ticketpark\FileBundle\FileProvider\FileProvider;

class FileProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $dir = 'foo/';
        $files = array(
            'foo' => 'bar',
            'ping' => 'pong'
        );

        $this->fileProvider = new FileProvider($dir, $files);
    }

    /**
     * @dataProvider getFilesProvider
     */
    public function testGetFile($filename, $expected)
    {
        $this->assertEquals($expected, $this->fileProvider->get($filename));
    }

    public function getFilesProvider()
    {
        return array(
            array('foo', 'foo/bar'),
            array('ping', 'foo/pong'),
        );
    }

    /**
     * @expectedException BadMethodCallException
     * @dataProvider getMissingFilesProvider
     */
    public function testMissingGetFile($filename)
    {
        $this->fileProvider->get($filename);
    }

    public function getMissingFilesProvider()
    {
        return array(
            array('bad'),
            array(null),
            array(''),
        );
    }
}
