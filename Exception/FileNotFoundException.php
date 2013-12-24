<?php

namespace Ticketpark\FileBundle\Exception;

class FileNotFoundException extends \RuntimeException
{
    /**
     * Constructor.
     *
     * @param string $path The path to the file that was not found
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The file "%s" does not exist or could not be accessed', $path));
    }
}
