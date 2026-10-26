<?php

namespace NAIF_CMS\System\Library;

trait FileUpload
{

    private $fileSize;
    private $fileExtension;
    private $newFileName;
    private $directory;
    private $file;
    private $error;

    public function __construct()
    {
        $this->setDirectory(UPLOAD_PATH);
        $this->setFileExtension(ALLOWED_FILE_EXTENSION);
        $this->setFileSize(ALLOWED_FILE_SIZE);
        if (!file_exists($this->getDirectory())) {
            mkdir($this->getDirectory(), 0777);
        }
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    public function setFile($file)
    {
        if (isset($_FILES[$file])) {
            $this->file = $_FILES[$file];
            $this->setNewFileName();
        }
    }

    public function renameFile()
    {
        return md5(random_bytes(32) . time());
    }

    public function getNewFileName()
    {
        return $this->newFileName;
    }

    public function getFileExtensionByFileName()
    {
        return strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
    }

    public function setNewFileName()
    {
        if (!empty($this->file) && $this->file['error'] !== 4) {
            $fileExtension     = $this->getFileExtensionByFileName();
            $this->newFileName = $this->renameFile() . '.' . $fileExtension;
        }
    }

    public function isValidFileSize()
    {
        if ($this->file['error'] !== 4) {
            return $this->file['size'] < $this->getFileSize();
        }
        return false;
    }

    public function isValidFileExtension()
    {
        if ($this->file['error'] !== 4) {
            $fileExtension = $this->getFileExtensionByFileName();
            return in_array($fileExtension, $this->getFileExtension());
        }
        return false;
    }

    public function isValidUpload()
    {
        if ($this->file['error'] !== 4) {
            return is_uploaded_file($this->file['tmp_name']);
        }
        return false;
    }

    public function isValidImage()
    {
        if ($this->file['error'] !== 4) {
            return @getimagesize($this->file['tmp_name']);
        }
        return false;
    }

    public function checkDirectoryIsWritable()
    {
        return is_writable($this->getDirectory());
    }

    public function setError($keyMessage, $data = [])
    {
        $message       = $this->Language->feedKey($keyMessage, $data);
        $this->error[] = $message;
        $this->Messenger->add($message, Messenger::TYPE_ERROR);
    }

    public function getError()
    {
        return $this->error;
    }

    public function startUpload($required = false, $isUploadImage = false)
    {
        if (!empty($this->file) && $this->file['error'] !== 4) {
            if (!$this->isValidImage() && $isUploadImage) {
                $this->setError('text_error_FileUpload_imageRead');
            } else if (!$this->isValidFileExtension()) {
                $this->setError('text_error_FileUpload_extension', [implode(' ', $this->getFileExtension())]);
            } else if (!$this->isValidFileSize()) {
                $this->setError('text_error_FileUpload_size', [round($this->getFileSize() / 1024 / 1024)]);
            } else if (!$this->checkDirectoryIsWritable()) {
                $this->setError('text_error_FileUpload_folderIsNotWritable');
            } else if (!$this->isValidUpload()) {
                $this->setError('text_error_FileUpload_upload');
            } else {
                if (move_uploaded_file($this->file['tmp_name'], $this->getDirectory() . $this->getNewFileName())) {
                    return true;
                }
            }
        }
        if (!empty($this->file) && $required && $this->file['error'] === 4) {
            $this->setError('text_error_FileUpload_required');
        }
    }

    public function isUploadedFile()
    {
        return empty($this->getError());
    }

}