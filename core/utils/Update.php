<?php

class Update
{

    public function __construct()
    {
    }

    /**
     * 
     * @return type
     */
    public function seleniumStandaloneVersion()
    {
        $downloadProcess = new DownloadProcess('http://selenium-release.storage.googleapis.com/', '', 'looking for updates..');
        
        $feed = simplexml_load_string($downloadProcess->getContents());
        $dateLast = 0;
        $lastVersion = null;
        foreach ($feed as $info) {
            $date = strtotime($info->LastModified);
            if (strpos($info->Key, 'standalone') > -1 && $date && $date > $dateLast) {
                $dateLast = $date;
                $lastVersion = $info;
            }
        }
        if( $lastVersion ){
            $standaloneServer = explode('/', $seleniumInfo->Key);
            $gridPathInfo = pathinfo($path);
            $gridPath = $gridPathInfo['dirname']. $gridPathInfo['basename'] . DIRECTORY_SEPARATOR;
            if( !file_exists($gridPath . $standaloneServer[1])){
                $download = new DownloadProcess(
                            'http://selenium-release.storage.googleapis.com/' . $seleniumUri, 
                            $gridPath . $standaloneServer[1], 
                            'updating to version: ' . $standaloneServer[1]
                        );
                echo PHP_EOL . 'saving file.. ';
                $download->saveFile();
            } else {
                echo PHP_EOL . 'up to date with: ' .$standaloneServer[1];
            }
        }
    }
    
    public function downloadLatestSeleniumStandaloneVersion( $seleniumUri )
    {
        
    }
    

}
