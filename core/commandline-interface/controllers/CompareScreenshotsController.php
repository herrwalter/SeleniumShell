<?php

class CompareScreenshotsController extends Controller
{

    const OPTION_PRINT_PROJECTS = '--printProjects';
    const OPTION_EMAIL = '-email';
    const OPTION_PROJECTNAME = '-project';

    /** @var ScreenshotSession */
    protected $session1;

    /** @var ScreenshotSession */
    protected $session2;
    protected $foundDifferances = 0;
    protected $differences = array();
    /**
     * @var string Path to the save place of the created differences.
     */
    protected $differencesFolder;
    protected $projectName;
    protected $sessionFolders;

    public function getOptionalArguments()
    {
        return array(
            self::OPTION_PRINT_PROJECTS,
            self::OPTION_PROJECTNAME,
            self::OPTION_EMAIL
        );
    }

    public function getMinNrOfRequiredArguments()
    {
        return 1;
    }

    public function run()
    {
        $this->projectName = ArgvHandler::getArgumentValue(self::OPTION_PROJECTNAME);
        $this->setSessionFolders();
        if (ArgvHandler::getArgumentValue(self::OPTION_PRINT_PROJECTS)) {
            $this->printAvailableProjects();
        } else if ($this->projectName) {
            $this->setCompareSessions();
            $this->createFolderForDifferences();
            $this->compareScreenshotSessions();
            $this->sendReport();
        }
        exit(0);
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
            if( $this->foundDifferances > 0 ){
                break;
            }
        }
    }

    protected function compareScreenshots(Image $img, Image $img2, $name)
    {
        $compare = new ImageCompare($img, $img2);
        if ($compare->getOffsetDimensions() !== null) {
            // we found some differances
            $slicer = new ImageSlicer($img2, $compare->getOffsetDimensions());
            $diffentImagename = $this->differencesFolder . DIRECTORY_SEPARATOR . 'diff-' . $name;
            imagejpeg($slicer->getSlice(), $diffentImagename, 100);
            $slicer->destroyImages();
            $this->differences[$name] = $diffentImagename;
            echo '   found some differance in name: ' . $name . PHP_EOL;
            $this->foundDifferances++;
        } else {
            echo '   no differances' . PHP_EOL;
        }
    }

    protected function createFolderForDifferences()
    {
        $this->differencesFolder = $this->session2->getPath() . DIRECTORY_SEPARATOR . 'compared-differences';
        if(!file_exists($this->differencesFolder)){
            mkdir($this->session2->getPath() . DIRECTORY_SEPARATOR . 'compared-differences', '0777');
        } else {
            foreach( scandir($this->differencesFolder) as $filename ){
                if(pathinfo($filename, PATHINFO_EXTENSION) == 'jpg' ){
                    unset($filename);
                }
            }
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
        $foldercontents = $this->getValidSessionFolders();
        if (count($foldercontents) <= 2) {
            throw new ErrorException('Cannot compare screenshots, there are less then 2 sessions available');
        }
        $lastTwoSessionFolders = array(array_pop($foldercontents), array_pop($foldercontents));
        foreach ($lastTwoSessionFolders as $filename) {
            $path = GENERATED_SCREENSHOTS_PATH . $filename;
            if (is_dir($path)) {
                $sessions[$this->getSessionTime($filename)] = new ScreenshotSession($filename);
            }
        }
        ksort($sessions);
        return array(array_pop($sessions), array_pop($sessions));
    }

    protected function setSessionFolders()
    {
        $this->sessionFolders = array_diff(scandir(GENERATED_SCREENSHOTS_PATH), array('.', '..'));
    }

    protected function getSessionFolders()
    {
        return $this->sessionFolders;
    }

    protected function getValidSessionFolders()
    {
        $validSessions = array();
        foreach ($this->getSessionFolders() as $sessionName) {
            $sessionTime = $this->getSessionTime($sessionName);
            $sessionProject = $this->getSessionProject($sessionName);
            // check if time is in the past and after may 13 2014
            if ($sessionTime < time() &&
                $sessionTime > 1400000000 &&
                $sessionProject == $this->projectName) {
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

    protected function printAvailableProjects()
    {
        $projects = array();
        foreach ($this->getSessionFolders() as $sessionName) {
            $projectName = $this->getSessionProject($sessionName);
            $projects[$projectName] = $projectName;
        }
        echo ' We have found sessions of the following projects: ' . PHP_EOL;
        foreach ($projects as $project) {
            echo "   - " . $project . PHP_EOL;
        }
    }
    /**
     * Sends report to setted email.
     * Will only send if email is set and if there are differences
     */
    public function sendReport()
    {
        $email = ArgvHandler::getArgumentValue(self::OPTION_EMAIL);
        if( $email && $this->foundDifferances > 0 ){
            $mail = new HtmlMail();
            $mail->addParagraph('We have found: ' . $this->foundDifferances . ' differances');
            foreach( $this->differences as $name => $image ){
                $mail->addParagraph($name);
                $mail->addImage($image);
            }
            $mail->addTo($email);
            $mail->setSubject('Screenshot compare of project: ' . $this->projectName);
            $mail->setFrom('herrwalter@gmail.com');
            $mail->send();
        }
    }

    public static function getHelpDescription()
    {
        return 'for comparing screenshots of the last two sessions';
    }

    
}
