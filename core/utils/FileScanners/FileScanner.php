<?php

class FileScanner
{

    protected $_dir;
    protected $_phpFiles;
    protected $_files;

    public function __construct($relativeDir = '')
    {
        $this->_files = array();
        if ($relativeDir !== '') {
            $this->_readDirRecursive($relativeDir);
        }
    }

    public function _setDir($dir)
    {
        $this->_dir = $dir;
        $this->_readDirRecursive($dir);
    }

    protected function validateFile($file)
    {
        return true;
    }

    private function _readDirRecursive($dir)
    {
        $curDirFiles = false;
        if (is_dir($dir)) {
            $curDirFiles = scandir($dir);
        } else {
            DebugLog::write($dir . ' is not a directory. ');
        }
        if ($curDirFiles) {
            foreach ($curDirFiles as $file) {

                if (is_dir($file)) {
                    // . and .. skipped
                } else if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                    $this->_files[$dir][] = DIRECTORY_SEPARATOR . $file;
                } else {
                    $this->_readDirRecursive($dir . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
    }

    public function getFiles()
    {
        return $this->_files;
    }

    public function getFilesInOneDimensionalArray()
    {
        $foundFiles = array();
        foreach ($this->_files as $dir => $files) {
            foreach ($files as $file) {
                if ($this->validateFile($dir . $file)) {
                    $foundFiles[] = $dir . $file;
                }
            }
        }
        return $foundFiles;
    }

}
