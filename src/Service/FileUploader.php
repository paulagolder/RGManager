<?php

// src/AppBundle/Service/FileUploader.php
namespace App\Service;

//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Psr\Log\LoggerInterface;

/*class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function xupload(UploadedFile $file)
    {
        $type=  $file->getMimeType();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getTargetDir(), $fileName);
        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}*/


class FileUploader
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function upload($uploadDir, $file, $filename)
    {
        try {

            $file->move($uploadDir, $filename);
        } catch (FileException $e){

            $this->logger->error('failed to upload image: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }
}
