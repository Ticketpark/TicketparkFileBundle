<?php

namespace Ticketpark\FileBundle\FileEncoder;

use Ticketpark\FileBundle\FileHandler\FileHandlerInterface;

class FileEncoder implements FileEncoderInterface
{
    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * @inheritDoc
     */
    public function base64($filePathOrUrl)
    {
        $filePath = $this->fileHandler->get($filePathOrUrl);

        $mimeType = mime_content_type($filePath);
        $base64 = 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($filePath));

        return $base64;
    }
}