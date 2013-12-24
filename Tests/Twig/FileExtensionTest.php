<?php

namespace Ticketpark\FileBundle\Tests\Twig;

use Ticketpark\FileBundle\Twig\FileExtension;

class FileExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fileExtension = new FileExtension($this->getFileEncoderMock());
    }

    public function testConvertFileToBase64()
    {
        $this->assertEquals('encodedFileContent', $this->fileExtension->convertFileToBase64('someFile'));
    }

    public function getFileEncoderMock()
    {
        $fileEncoder = $this->getMockBuilder('Ticketpark\FileBundle\FileEncoder\FileEncoder')
            ->disableOriginalConstructor()
            ->setMethods(array('base64'))
            ->getMock();

        $fileEncoder->expects($this->once())
            ->method('base64')
            ->will($this->returnValue('encodedFileContent'));

        return $fileEncoder;
    }
}
