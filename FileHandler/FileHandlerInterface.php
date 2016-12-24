<?php

namespace Ticketpark\FileBundle\FileHandler;

interface FileHandlerInterface
{
    /**
     * Get file, regardless whether it is a file or an url
     *
     * @param  $pathOrUrl
     * @return File
     */
    public function get($pathOrUrl);

    /**
     * Downloads an external file and caches it locally
     *
     * @param  string $url
     * @return File
     */
    public function download($url);

    /**
     * Get a file from cache
     *
     * @param string $identifier  File identifier
     * @param array  $parameters  Use to define versions of the same file (e.q. an image in a specific size)
     * @return bool|File
     */
    public function fromCache($identifier, $parameters=array());

    /**
     * Writes a file to cache
     *
     * @param string $fileContents
     * @param string $identifier   File identifier
     * @param array  $parameters   Use to define versions of the same file (e.q. an image in a specific size)
     * @return bool|File
     */
    public function cache($content, $identifier, $parameters=array());
}