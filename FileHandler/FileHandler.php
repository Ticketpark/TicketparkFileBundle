<?php

namespace Ticketpark\FileBundle\FileHandler;

use Buzz\Browser;
use Ticketpark\FileBundle\Exception\FileNotFoundException;
use Ticketpark\FileBundle\Exception\InvalidArgumentException;

class FileHandler implements FileHandlerInterface
{
    /**
     * Constructor
     *
     * @param Browser $buzz
     * @param string $cacheDir
     */
    public function __construct(Browser $buzz, $cacheDir)
    {
        $this->buzz       = $buzz;
        $this->cacheDir   = $cacheDir;

        //make sure there is a trailing slash
        if (substr($this->cacheDir, -1) !== DIRECTORY_SEPARATOR) {
            $this->cacheDir .= DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @inheritDoc
     */
    public function get($pathOrUrl)
    {
        if ('http://' == substr($pathOrUrl, 0, 7) || 'https://' == substr($pathOrUrl, 0, 8)) {

            return $this->download($pathOrUrl);
        }

        if (!file_exists($pathOrUrl) || !is_file($pathOrUrl)) {

            throw new FileNotFoundException($pathOrUrl);
        }

        return $pathOrUrl;
    }

    /**
     * @inheritDoc
     */
    public function download($url)
    {
        if (!$downloadedFile = $this->fromCache($url)) {

            $response = $this->buzz->get($url);
            if (200 != $response->getStatusCode()) {
                throw new FileNotFoundException($url);
            }

            $downloadedFile = $this->cache($response->getContent(), $url);
        }

        return $downloadedFile;
    }

    /**
     * @inheritDoc
     */
    public function fromCache($identifier, $parameters=array())
    {
        $cachePath = $this->getCachePath($identifier, $parameters);

        if(is_file($cachePath) && is_readable($cachePath)){

            return $cachePath;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function cache($content, $identifier, $parameters=array())
    {
        if ('' == trim($identifier) || null == $identifier) {
            throw new InvalidArgumentException('You must define a file identifier.');
        }

        $cachePath = $this->getCachePath($identifier, $parameters);
        $dir = dirname($cachePath);

        if (!is_dir($dir)) {
            $this->mkdirRecursive($dir);
        }

        if (is_writable($dir)) {
            file_put_contents($cachePath, $content);

            return $cachePath;
        }

        return false;
    }

    /**
     * Get the path to the cached file
     *
     * @param string $identifier
     * @param array  $parameters
     * @return string
     */
    protected function getCachePath($identifier, $parameters=array())
    {
        $cacheFilename = hash('sha256', $identifier . serialize($parameters));

        return $this->cacheDir.$cacheFilename;
    }

    /**
     * Creates missing dirs recursively
     *
     * @param $dir
     */
    protected function mkdirRecursive($dir)
    {
        $dirParts = explode('/', $dir);
        $dir = '';

        foreach($dirParts as $dirPart){
            $dir .= $dirPart.'/';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
        }
    }
}