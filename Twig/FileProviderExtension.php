<?php

namespace Ticketpark\FileBundle\Twig;

use Ticketpark\FileBundle\FileProvider\FileProvider;

class FileProviderExtension extends \Twig_Extension
{
    public function __construct(FileProvider $fileProvider)
    {
        $this->fileProvider = $fileProvider;
    }

    /**
     * Get file
     *
     * @param string $fileIdentifier
     * @return string
     */
    public function getFile($fileIdentifier)
    {
        return $this->fileProvider->get($fileIdentifier);
    }


    public function getName()
    {
        return 'ticketpark_file_provider_extension';
    }
}