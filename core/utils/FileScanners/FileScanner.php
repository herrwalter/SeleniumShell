<?php

class FileScanner
{

    protected $dir;
    protected $phpFiles;
    protected $files;

    /** @var FileScannerFilter */
    protected $filter;

    public function __construct($relativeDir = '')
    {
        $this->files = array();
        if ($relativeDir !== '') {
            $this->readDirRecursive($relativeDir);
        }
    }

    public function applyFilter(FileScannerFilter $fileScannerFilter)
    {
        $this->filter = $fileScannerFilter;
    }

    public function _setDir($dir)
    {
        $this->dir = $dir;
        $this->readDirRecursive($dir);
    }

    /**
     * Used as a override to filter a file.
     * 
     * @param string $file
     * @return boolean if file is valid
     */
    protected function validateFile($file)
    {
        return true;
    }

    protected function scanDir($dir)
    {
        $files = scandir($dir);
        if ($this->filter !== null) {
            $files = $this->filter->filterFiles($files);
        }
        return $files;
    }

    private function readDirRecursive($dir)
    {
        $curDirFiles = false;
        if (is_dir($dir)) {
            $curDirFiles = $this->scanDir($dir);
        } else {
            DebugLog::write($dir . ' is not a directory. ');
        }
        if ($curDirFiles) {
            foreach ($curDirFiles as $file) {
                if ($file == '.' || $file == '..') {
                    // . and .. skipped
                } else if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                    $this->files[$dir][] = DIRECTORY_SEPARATOR . $file;
                } else {
                    $this->readDirRecursive($dir . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getFilesInOneDimensionalArray()
    {
        $foundFiles = array();
        foreach ($this->files as $dir => $files) {
            foreach ($files as $file) {
                if ($this->validateFile($dir . $file)) {
                    $foundFiles[] = $dir . $file;
                }
            }
        }
        return $foundFiles;
    }

}
