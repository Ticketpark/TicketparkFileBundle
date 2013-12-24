<?php

namespace Ticketpark\FileBundle\Twig;

use Ticketpark\FileBundle\FileEncoder\FileEncoderInterface;

class FileExtension extends \Twig_Extension
{
    public function __construct(FileEncoderInterface $fileEncoder)
    {
        $this->fileEncoder = $fileEncoder;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('base64', array($this, 'convertFileToBase64')),
        );
    }

    /**
     * Converts a file to base64 content string for inclusion in templates
     *
     * @param  string $filePath
     * @return string
     */
    public function convertFileToBase64($filePath)
    {
        return $this->fileEncoder->base64($filePath);
    }

    public function getName()
    {
        return 'ticketpark_file_extension';
    }
}