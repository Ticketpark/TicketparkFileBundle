<?php

namespace Ticketpark\FileBundle\FileEncoder;

interface FileEncoderInterface
{
    /**
     * Gets file content as base64 string
     *
     * @param $filePath
     * @return string
     */
    public function base64($filePath);
}