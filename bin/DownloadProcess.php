<?php

class DownloadProcess
{

    protected $_savePath;
    protected $_file;
    protected $_fileSize;
    protected $_steamContext;
    protected $_outputMessage;
    protected $_fileContents;

    public function __construct($file, $savePath, $outputMessage = '')
    {
        
        $this->_file = $file;
        $this->_savePath = $savePath;
        $this->_outputMessage = $outputMessage;
        $this->_steamContext = stream_context_create();
        $this->_prepareSteamContext();
        $this->_start();
    }
    
    private function _prepareSteamContext()
    {
        stream_context_set_params($this->_steamContext, array("notification" => "DownloadProcess::stream_notification_callback"));
    }

    private function _start()
    {
        $this->_fileContents = file_get_contents($this->_file, false, $this->_steamContext);
    }
    
    public  function saveFile(){
        file_put_contents($this->_savePath, $this->_fileContents);
    }
    
    public function getContents(){
        return $this->_fileContents;
    }
    

    public function stream_notification_callback($notification_code, $severity, $message, $message_code, $bytes_transferred, $bytes_max)
    {
        static $fileSize;
        switch ($notification_code) {
            case STREAM_NOTIFY_FILE_SIZE_IS:
                $this->_fileSize = $bytes_max;
                break;
            case STREAM_NOTIFY_PROGRESS:
                if ($bytes_transferred > 0) {
                    if (!isset($this->_fileSize)) {
                        printf("\rUnknown filesize.. %2d kb done..", $bytes_transferred / 1024);
                    } else {
                        $length = (int) (($bytes_transferred / $this->_fileSize) * 100);
                        printf("\r%s %s%%",$this->_outputMessage, $length);
                    }
                }
                break;
            default:
                break;
        }
    }

}
