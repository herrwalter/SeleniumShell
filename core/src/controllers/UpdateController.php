<?php

class UpdateController extends Controller
{

    protected $path;
    
    public function run()
    {
        $question = new PathCLQuestion("What is your selenium grid downloadfolder?");
        $question->askQuestion();
        $this->setPath($question->getAwnser());
        $this->updateSeleniumStandaloneServer();
        $this->updateChromeDriver();
        $this->updateInternetExplorerDriver();
    }
    
    public function setPath( $path ){
        $lastchar = substr($path, -1);
        // strip the dir seperator at the end if needed.
        ($lastchar == DIRECTORY_SEPARATOR) ? $path = substr($path, 0, -1) : $path = $path;
        // if the path is a file then we will use the dir of the file.
        if( pathinfo($path, PATHINFO_EXTENSION) !== ''){
            $path = pathinfo($path, PATHINFO_DIRNAME);
        }
        $this->path = $path;
    }
    
    public function updateChromeDriver()
    {
        echo PHP_EOL;
        $chromeUpdate = new DownloadProcess('http://chromedriver.storage.googleapis.com/LATEST_RELEASE', '', 'looking for latest version');
        echo PHP_EOL;
        $version = $chromeUpdate->getContents();
        $chromeZip = new DownloadProcess(
                'http://chromedriver.storage.googleapis.com/' .$version. '/chromedriver_win32.zip', 
                DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.zip',
                'downloading latest chromedriver.zip (version '.$version.') .. ');
        $chromeZip->saveFile();
        $curpath = getcwd();
        chdir(DOWNLOADS_PATH . DIRECTORY_SEPARATOR);
        exec('"%java_home%\bin\jar" xf chromedriver.zip', $lines, $exitcode);
        if( $exitcode > 0 ){
            throw new ErrorException( 'Could not unzip the files, do you have a JAVA_HOME environemntal variable?' );
        }
        exec('copy chromedriver.exe '.$this->path , $lines, $exitcode);
        exec('copy chromedriver.exe '.BIN_PATH);
        if(file_exists(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.exe') ){
            unlink(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'chromedriver.exe');
        }
        chdir($curpath);
    }
    
    public function updateInternetExplorerDriver()
    {
        echo PHP_EOL;
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
            throw new ErrorException( 'Could not unzip the files, do you have a JAVA_HOME environemntal variable?' );
        }
        
        exec('copy IEDriverServer.exe '.$this->path);
        exec('copy IEDriverServer.exe '.BIN_PATH);
        if(file_exists(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'IEDriverServer.exe')){
            unlink(DOWNLOADS_PATH . DIRECTORY_SEPARATOR . 'IEDriverServer.exe');
        }
        chdir($curpath);       
    }
    
    public function updateSeleniumStandaloneServer()
    {
        echo PHP_EOL;
        $downloadProcess = new DownloadProcess('http://selenium-release.storage.googleapis.com/', '', 'looking for updates..');
        echo PHP_EOL;
        // reed the feed that contains the version number of the sss
        $feed = simplexml_load_string($downloadProcess->getContents());
        $dateLast = 0;
        $lastVersion = null;
        // fetch the latests version by date
        foreach ($feed as $info) {
            $date = strtotime($info->LastModified);
            if (strpos($info->Key, 'standalone') > -1 && $date && $date > $dateLast) {
                $dateLast = $date;
                $lastVersion = $info;
            }
        }
        if( $lastVersion ){
            $standaloneServer = explode('/', $lastVersion->Key);
            
            if( !file_exists($this->path . DIRECTORY_SEPARATOR . $standaloneServer[1])){
                $download = new DownloadProcess(
                            'http://selenium-release.storage.googleapis.com/' . $lastVersion->Key, 
                            $this->path . DIRECTORY_SEPARATOR . $standaloneServer[1], 
                            'updating to version: ' . $standaloneServer[1]
                        );
                echo PHP_EOL . 'saving file.. ';
                $download->saveFile();
                // also save to binpath.
                chmod(BIN_PATH, 0777);
                $download->setSavePath(BIN_PATH . DIRECTORY_SEPARATOR . $standaloneServer[1]);
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
