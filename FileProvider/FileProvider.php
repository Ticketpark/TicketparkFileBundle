<?php

namespace Ticketpark\FileBundle\FileProvider;

class FileProvider
{
    /**
     * @param string $dir    Root directory of files
     * @param array  $files  Array of files, array('fileIdentifier' => 'filename.txt')
     */
    public function __construct($dir, $files)
    {
        $this->dir    = $dir;
        $this->files  = $files;
    }

    /**
     * Get file
     *
     * @param  $fileIdentifier
     * @return string  Full path to file
     */
    public function get($fileIdentifier)
    {
        if (isset($this->files[$fileIdentifier])) {
            return $this->dir.$this->files[$fileIdentifier];
        }

        throw new \BadMethodCallException(sprintf('File "%s" is not defined', $fileIdentifier));
    }
}