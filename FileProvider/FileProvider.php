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
        if (!isset($this->files[$fileIdentifier])) {
            throw new \BadMethodCallException(sprintf('File "%s" is not defined', $fileIdentifier));
        }

        $file = $this->files[$fileIdentifier];
        $dir  = $this->dir;

        // Handle trailing/starting directory separators to minimize setup errors
        if (isset($this->files[$fileIdentifier])) {
            if (substr($this->dir, -1) !== DIRECTORY_SEPARATOR) {
                if ($this->files[$fileIdentifier][0] !== DIRECTORY_SEPARATOR) {
                    $dir .= DIRECTORY_SEPARATOR;
                }
            } else {
                if ($this->files[$fileIdentifier][0] == DIRECTORY_SEPARATOR) {
                    $file = substr($file, 1);
                }
            }

            return $dir.$file;
        }
    }
}