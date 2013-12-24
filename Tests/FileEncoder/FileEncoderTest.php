<?php

namespace Ticketpark\FileBundle\Tests\File;

use Ticketpark\FileBundle\FileEncoder\FileEncoder;

class FileEncoderTest extends \PHPUnit_Framework_TestCase
{
    protected $testFile;

    public function testBase64Encode()
    {
        $this->testFile = __DIR__.'/../../Test/Files/testfile.txt';
        $fileEncoder = new FileEncoder($this->getFileHandlerMock());

        $this->assertEquals('data:text/plain;base64,TG9yZW0gaXBzdW0gZG9sb3I=', $fileEncoder->base64($this->testFile));
    }

    public function getFileHandlerMock()
    {
        $fileHandler = $this->getMockBuilder('Ticketpark\FileBundle\FileHandler\FileHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $fileHandler->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->testFile));

        return $fileHandler;
    }
}
