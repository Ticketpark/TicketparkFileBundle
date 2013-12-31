<?php

namespace Ticketpark\FileBundle\Tests\Twig;

use Ticketpark\FileBundle\Twig\FileProviderExtension;

class FileProviderExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fileExtension = new FileProviderExtension($this->getFileProviderMock(), 'foo');
    }

    public function testGetFile()
    {
        $this->assertEquals('filename', $this->fileExtension->getFile('someFile'));
    }

    public function getFileProviderMock()
    {
        $fileHandler = $this->getMockBuilder('Ticketpark\FileBundle\FileProvider\FileProvider')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $fileHandler->expects($this->any())
            ->method('get')
            ->will($this->returnValue('filename'));

        return $fileHandler;
    }
}
