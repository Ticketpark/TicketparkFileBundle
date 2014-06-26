<?php

namespace Ticketpark\FileBundle\Tests\FileProvider;

use Ticketpark\FileBundle\FileProvider\FileProvider;

class FileProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFilesProvider
     */
    public function testGetFile($setup, $filename, $expected)
    {
        $fileProvider = new FileProvider($setup['dir'], $setup['files']);
        $this->assertEquals($expected, $fileProvider->get($filename));
    }

    public function getFilesProvider()
    {
        $setup1 = array(
            'dir' => 'foo/',
            'files' => array(
                'foo' => 'bar',
                'ping' => 'pong'
            )
        );

        $setup2 = array(
            'dir' => 'foo/',
            'files' => array(
                'foo' => '/bar',
                'ping' => 'pong'
            )
        );

        $setup3 = array(
            'dir' => 'foo',
            'files' => array(
                'foo' => '/bar',
                'ping' => 'pong'
            )
        );

        return array(
            array($setup1, 'foo', 'foo/bar'),
            array($setup1, 'ping', 'foo/pong'),
            array($setup2, 'foo', 'foo/bar'),
            array($setup2, 'ping', 'foo/pong'),
            array($setup3, 'foo', 'foo/bar'),
            array($setup3, 'ping', 'foo/pong'),
        );
    }

    /**
     * @expectedException BadMethodCallException
     * @dataProvider getMissingFilesProvider
     */
    public function testMissingGetFile($filename)
    {
        $dir = 'foo/';
        $files = array(
            'foo' => 'bar',
            'ping' => 'pong'
        );

        $fileProvider = new FileProvider($dir, $files);
        $fileProvider->get($filename);
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
