<?php

class CompareScreenshotsController extends Controller
{

    /** @var ScreenshotSession */
    protected $session1;

    /** @var ScreenshotSession */
    protected $session2;
    protected $foundDifferances = 0;
    protected $differences = array();
    protected $projectName;

    public function getOptionalArguments()
    {
        return array('-path');
    }

    public function getMandatoryArguments()
    {
        return array('-project');
    }

    public function run()
    {
        $this->projectName = ArgvHandler::getArgumentValue('-project');
        $this->setCompareSessions();
        $this->createFolderForDifferences();
        $this->compareScreenshotSessions();
        exit($this->foundDifferances);
    }

    protected function compareScreenshotSessions()
    {
        $screenshots = $this->session1->getScreenshots();
        foreach ($screenshots as $name => $screenshot) {
            if ($this->session2->hasScreenshot($name)) {
                echo 'now comparing screenshot ' . $name . '.. ' . PHP_EOL;
                $this->compareScreenshots($screenshot, $this->session2->getScreenshot($name), $name);
            } else {
                echo $name . ' cannot be found in one the sessions. ' . PHP_EOL;
            }
        }
    }

    protected function compareScreenshots(Image $img, Image $img2, $name)
    {
        $compare = new ImageCompare($img, $img2);
        if ($compare->getOffsetDimensions() !== null) {
            $slicer = new ImageSlicer($img2, $compare->getOffsetDimensions());
            imagejpeg($slicer->getSlice(), 'C:\SeleniumTests\SeleniumShell\generated\screenshots\diff-' . $name, 100);
            echo '   found some differance in name: ' . $name . PHP_EOL;
            //echo '   location: ' . PHP_EOL;
            //echo '       C:\SeleniumTests\SeleniumShell\generated\screenshots\diff-' . $name . PHP_EOL;
            $this->foundDifferances++;
        } else {
            echo '   no differances' . PHP_EOL;
        }
    }

    protected function createFolderForDifferences()
    {
        
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
        $foldercontents = $this->getSessionFolders();
        if (count($foldercontents) <= 2) {
            throw new ErrorException('Cannot compare screenshots, there are less then 2 sessions available');
        }
        $foldercontents = array(array_pop($foldercontents), array_pop($foldercontents));
        foreach ($foldercontents as $filename) {
            $path = GENERATED_SCREENSHOTS_PATH . $filename;
            if (is_dir($path)) {
                $sessions[$this->getSessionTime($filename)] = new ScreenshotSession($filename);
            }
        }
        ksort($sessions);
        return array(array_pop($sessions), array_pop($sessions));
    }

    protected function getSessionFolders()
    {
        $foldercontents = array_diff(scandir(GENERATED_SCREENSHOTS_PATH), array('.', '..'));
        $validSessions = array();
        foreach ($foldercontents as $sessionName) {
            $sessionTime = $this->getSessionTime($sessionName);
            $sessionProject = $this->getSessionProject($sessionName);
            // check if time is in the past and after may 13 2014
            if ($sessionTime < time() && 
                $sessionTime > 1400000000 && 
                $sessionProject == $this->projectName ) {
                $validSessions[] = $sessionName;
            }
        }
        asort($validSessions);
        return $validSessions;
    }

    protected function getSessionTime($sessionName)
    {
        $ex = explode('-', $sessionName);
        return (int) $ex[0];
    }

    protected function getSessionProject($sessionName)
    {
        $ex = explode('-', $sessionName);
        return $ex[1];
    }

    public static function getHelpDescription()
    {
        return 'for comparing screenshots of the last two sessions';
    }

}
