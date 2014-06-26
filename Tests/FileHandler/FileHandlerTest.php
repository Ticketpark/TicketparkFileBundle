<?php

namespace Ticketpark\FileBundle\Tests\File;

use Ticketpark\FileBundle\FileHandler\FileHandler;

class FileHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->standardCacheDir = __DIR__.'/../../Test/Files/cache/';
        $this->getInstance($this->standardCacheDir, false);
    }

    public function getInstance($cacheDir, $buzzBrowserExpected, $buzzBrowserResponseStatus=200)
    {
        $this->cacheDir = $cacheDir;
        $this->fileHandler = new FileHandler($this->getBuzzBrowserMock($buzzBrowserExpected, $buzzBrowserResponseStatus), $cacheDir);
    }

    public function testGetWithFile()
    {
        $this->getInstance($this->standardCacheDir, false);

        $file = $this->standardCacheDir.'../testfile.txt';
        $result = $this->fileHandler->get($file);
        $this->assertEquals($result, $file);
    }

    public function testGetWithFileCacheDirNoTrailingSlash()
    {
        $this->getInstance(substr($this->standardCacheDir, 0, -1), false);

        $file = $this->standardCacheDir.'../testfile.txt';
        $result = $this->fileHandler->get($file);
        $this->assertEquals($result, $file);
    }

    public function testGetWithUrl()
    {
        $this->getInstance($this->standardCacheDir, true);

        $result = $this->fileHandler->get('http://www.foo.com');
        $this->assertEquals($result, $this->cacheDir.'857acf27115c29dd939e7767d9cdd0bab0f22a44a0de637cac966681f982f2fe');

        //clean up
        unlink($result);
    }

    /**
     * @expectedException Ticketpark\FileBundle\Exception\FileNotFoundException
     */
    public function testGetWithInexistentFile()
    {
        $this->getInstance($this->standardCacheDir, false);

        $file = $this->standardCacheDir.'../inexistent.txt';
        $this->fileHandler->get($file);
    }


    public function testDownload()
    {
        $this->getInstance($this->standardCacheDir, true);
        $result = $this->fileHandler->download('http://www.foo.com');
        $this->assertEquals($result, $this->cacheDir.'857acf27115c29dd939e7767d9cdd0bab0f22a44a0de637cac966681f982f2fe');

        //clean up
        unlink($result);
    }

    public function testDownloadAlreadyInCache()
    {
        $result = $this->fileHandler->download('http://www.bar.com');
        $this->assertEquals($result, $this->cacheDir.'78b22bc0b8b7d95b72e14c37dd2c4c9192cc0b981aa3253a335f3cea624d334f');
    }

    /**
     * @expectedException Ticketpark\FileBundle\Exception\FileNotFoundException
     */
    public function testDownloadUnavailableUrl()
    {
        $this->getInstance($this->standardCacheDir, true, 404);
        $this->fileHandler->download('http://www.xyx.com');
    }

    public function testGetFromCache()
    {
        $result = $this->fileHandler->fromCache('file.txt');
        $this->assertEquals($result, $this->cacheDir.'50f12f08bb917ce0e0743e4e5ce70af9589c006aad00226f3bac48a25c8b96dd');

        $result = $this->fileHandler->fromCache('file.txt', array('bar', 'bar'));
        $this->assertEquals($result, $this->cacheDir.'f4e595e859d2e13d3a88eaa1134e83c9e31c3f328c9f8d464788611335b72f47');

        $result = $this->fileHandler->fromCache('bar.txt');
        $this->assertFalse($result);
    }

    public function testCacheFileWorks()
    {
        $result = $this->fileHandler->cache('foo', 'file.txt');
        $this->assertEquals($result, $this->cacheDir.'50f12f08bb917ce0e0743e4e5ce70af9589c006aad00226f3bac48a25c8b96dd');

        $result = $this->fileHandler->cache('foo', 'file.txt', array('bar', 'bar'));
        $this->assertEquals($result, $this->cacheDir.'f4e595e859d2e13d3a88eaa1134e83c9e31c3f328c9f8d464788611335b72f47');
    }

    public function testCacheFileToUnwritableDir()
    {
        if (is_writeable('/')) {
            $this->markTestSkipped(
                'We need a unwritable directory to test this - usually there is a good chance with the root dir.
                 Well, not with you :)'
            );
            return;
        }

        $this->getInstance('/', false);
        $result = $this->fileHandler->cache('foo', 'file.txt');
        $this->assertFalse($result);
    }

    /**
     * @expectedException Ticketpark\FileBundle\Exception\InvalidArgumentException
     */
    public function testCacheFileWithoutFile()
    {
        $this->fileHandler->cache('foo', '');
    }

    public function getBuzzBrowserMock($buzzBrowserExpected, $buzzBrowserResponseStatus)
    {
        $buzzBrowser = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $expected = $this->never();
        if ($buzzBrowserExpected) {
            $expected = $this->once();
        }

        $buzzBrowser->expects($expected)
            ->method('get')
            ->will($this->returnValue(call_user_func(array($this, 'getResponseMock'), $buzzBrowserResponseStatus)));

        return $buzzBrowser;
    }

    public function getResponseMock($buzzBrowserResponseStatus)
    {
        $response = $this->getMockBuilder('Buzz\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getStatusCode', 'getContent'))
            ->getMock();

        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($buzzBrowserResponseStatus));

        $response->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('foo'));

        return $response;
    }
}
