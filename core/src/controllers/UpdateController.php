<?php

class UpdateController extends Controller
{
    public function getMandatoryArguments()
    {
        return array();
    }

    public function run()
    {
        echo "What is your selenium grid downloadfolder? " . PHP_EOL;
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if( file_exists(trim($line)) ){
            $gridPath = trim($line);
            $update = new Update();
            $seleniumInfo = $update->getLastModifiedFeedByKeyContaining('standalone');
            if( $seleniumInfo ){
                $standaloneServer = explode('/', $seleniumInfo->Key);
                $gridPathInfo = pathinfo($gridPath);
                $gridPath = $gridPathInfo['dirname']. $gridPathInfo['basename'] . DIRECTORY_SEPARATOR;
                if( !file_exists($gridPath . $standaloneServer[1])){
                    $download = new DownloadProcess(
                                'http://selenium-release.storage.googleapis.com/' . $seleniumInfo->Key, 
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
    }

    public static function getHelpDescription()
    {
        return 'For updating you selenium-shell-standalone-server';
    }

}
