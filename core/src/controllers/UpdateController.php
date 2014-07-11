<?php

class UpdateController extends Controller
{

    protected $path;
    
    public function run()
    {
        $question = new PathCLQuestion("What is your selenium grid downloadfolder?");
        $question->askQuestion();
        $this->path = $question->getAwnser();
        $this->updateSeleniumStandaloneServer();
        $this->updateChromeDriver();
        $this->updateInternetExplorerDriver();
    }
    
    public function updateChromeDriver()
    {
        echo PHP_EOL;
        $chromeUpdate = new DownloadProcess('http://chromedriver.storage.googleapis.com/LATEST_RELEASE', '', 'looking for latest version');
        $version = $chromeUpdate->getContents();
        $chromeZip = new DownloadProcess(
                'http://chromedriver.storage.googleapis.com/' .$version. '/chromedriver_win32.zip', 
                DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.zip',
                'downloading latests chromedriver.zip.. ');
        $chromeZip->saveFile();
        $curpath = getcwd();
        chdir(DOWNLOADS_PATH . DIRECTORY_SEPARATOR);
        exec('"%java_home%\bin\jar" xf chromedriver.zip', $lines, $exitcode);
        if( $exitcode > 0 ){
            var_dump($lines);
        }
        exec('copy chromedriver.exe '.BIN_PATH);
        if(file_exists(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.exe') ){
            unlink(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.exe');
        }
        chdir($curpath);
    }
    
    public function updateInternetExplorerDriver()
    {
        $downloadProcess = new DownloadProcess('http://selenium-release.storage.googleapis.com/', '', 'looking for updates..');
        echo PHP_EOL;
        $feed = simplexml_load_string($downloadProcess->getContents());
        $dateLast = 0;
        $lastVersion = null;
        foreach ($feed as $info) {
            $date = strtotime($info->LastModified);
            if (strpos($info->Key, 'IEDriver') > -1 && $date && $date > $dateLast) {
                $dateLast = $date;
                $lastVersion = $info;
            }
        }      
        if( $lastVersion ){
            $standaloneServer = explode('/', $lastVersion->Key);
            $gridPathInfo = pathinfo($this->path);
            $gridPath = $gridPathInfo['dirname']. $gridPathInfo['basename'] . DIRECTORY_SEPARATOR;
            if( !file_exists(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . $standaloneServer[1])){
                $download = new DownloadProcess(
                            'http://selenium-release.storage.googleapis.com/' . $lastVersion->Key, 
                            DOWNLOADS_PATH . DIRECTORY_SEPARATOR . $standaloneServer[1], 
                            'updating to version: ' . $standaloneServer[1]
                        );
                echo PHP_EOL . 'saving file.. ';
                $download->saveFile();
            } else {
                echo PHP_EOL . 'up to date with: ' .$standaloneServer[1];
            }
        }
         $curpath = getcwd();
        chdir(DOWNLOADS_PATH . DIRECTORY_SEPARATOR);
        exec('"%java_home%\bin\jar" xf '. $standaloneServer[1], $lines, $exitcode);
        if( $exitcode > 0 ){
            var_dump($lines);
        }
        exec('copy IEDriverServer.exe '.BIN_PATH);
        if(file_exists(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'IEDriverServer.exe')){
            unlink(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'IEDriverServer.exe');
        }
        chdir($curpath);       
    }
    
    public function updateSeleniumStandaloneServer()
    {
        $downloadProcess = new DownloadProcess('http://selenium-release.storage.googleapis.com/', '', 'looking for updates..');
        echo PHP_EOL;
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
            $standaloneServer = explode('/', $lastVersion->Key);
            $gridPathInfo = pathinfo($this->path);
            $gridPath = $gridPathInfo['dirname']. $gridPathInfo['basename'] . DIRECTORY_SEPARATOR;
            if( !file_exists($gridPath . $standaloneServer[1])){
                $download = new DownloadProcess(
                            'http://selenium-release.storage.googleapis.com/' . $lastVersion->Key, 
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

    public static function getHelpDescription()
    {
        return 'For updating you selenium-shell-standalone-server';
    }

}
