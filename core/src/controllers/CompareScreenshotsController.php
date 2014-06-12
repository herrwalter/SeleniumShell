<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CompareScreenshotsController extends Controller
{
    /** @var ScreenshotSession */
    protected $session1;
    /** @var ScreenshotSession */
    protected $session2;

    public function getMandatoryArguments()
    {
    }

    public function run()
    {
        $this->setCompareSessions();
        $this->compareScreenshotSessions();
    }

    protected function compareScreenshotSessions()
    {
        $screenshots = $this->session1->getScreenshots();
        foreach($screenshots as $name => $screenshot ){
            if( $this->session2->hasScreenshot($name) ){
                echo 'now comparing screenshot' . $name . '.. ' . PHP_EOL;
                $this->compareScreenshots($screenshot, $this->session2->getScreenshot($name), $name );
            }
            
        }
    }
    
    protected function compareScreenshots(Image $img, Image $img2, $name){
        $compare = new ImageCompare($img, $img2);
        if( $compare->getOffsetDimensions() !== null ){
            $slicer = new ImageSlicer($img2, $compare->getOffsetDimensions());
            imagejpeg($slicer->getSlice(), 'C:\SeleniumTests\SeleniumShell\generated\screenshots\diff-' . $name , 100 );
            echo '   found a differance.. '.PHP_EOL;
            echo '   location: '.PHP_EOL;
            echo '       C:\SeleniumTests\SeleniumShell\generated\screenshots\diff-' . $name . PHP_EOL;
        } else {
            echo '   no differances' .PHP_EOL;
        }
    }

    protected function setCompareSessions()
    {
        $sessions = $this->getLastTwoSessions();
        $this->session1 = array_shift($sessions);
        $this->session2 = array_shift($sessions);
    }

    public function getLastTwoSessions()
    {
        $sessions = array();
        $foldercontents = array_diff(scandir(GENERATED_SCREENSHOTS_PATH), array('.', '..'));
        foreach ($foldercontents as $filename) {
            $path = GENERATED_SCREENSHOTS_PATH . $filename;
            if (is_dir($path)) {
                $sessions[$this->getSessionTime($filename)] = new ScreenshotSession($filename);
            }
        }
        ksort($sessions);
        return array(array_pop($sessions), array_pop($sessions));
    }

    public function getSessionTime($sessionName)
    {
        $ex = explode('-', $sessionName);
        return (int) $ex[0];
    }

    public function getHelpDescription()
    {
        return 'for comparing screenshots of the last two sessions';
    }

}
