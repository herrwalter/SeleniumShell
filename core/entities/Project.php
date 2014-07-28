<?php

class Project
{

    private $_name;
    private $_path;

    /** @var ConfigHandler */
    private $_config;
    private $_browsers = array();
    private $_coreConfig;

    public function __construct($projectName, ConfigHandler $coreConfig)
    {
        $this->_coreConfig = $coreConfig;
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectConfig();
        $this->_setBrowsers();
    }

    private function _setProjectName($projectName)
    {
        $this->_name = $projectName;
    }

    private function _setProjectPath()
    {
        // check if project path is set by project section config. 
        // else use the defaults path
        if ($this->_coreConfig->sectionExists('project-' . $this->_name)) {
            $path = $this->_coreConfig->getAttribute('path', 'project-' . $this->_name);
        } else {
            $path = PROJECTS_FOLDER . DIRECTORY_SEPARATOR . $this->_name;
        }

        if (is_dir($path)) {
            $this->_path = $path;
        } else {
            throw new ErrorException('Project not found. Checked the path: ' . $path);
        }
    }

    private function _setTestSuitesPath()
    {
        $this->_testSuitesPath = $this->_path . DIRECTORY_SEPARATOR . 'testsuites';
    }

    private function _setProjectConfig()
    {
        $this->_config = new ConfigHandler($this->_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'project.ini');
    }

    private function _setBrowsers()
    {
        $browsers = $this->_getDefinedBrowsers();
        foreach ($browsers as $browser) {
            $browserSection = 'browser-' . $browser;
            if ($this->_coreConfig->sectionExists($browserSection)) {
                $browserName = $this->_coreConfig->getAttribute('browserName', $browserSection);
                $version = $this->_coreConfig->getAttribute('version', $browserSection);
                $platform = $this->_coreConfig->getAttribute('platform', $browserSection);
                $this->_browsers[] = new Browser($browser, $browserName, $version, $platform);
            }
        }
    }

    private function _getDefinedBrowsers()
    {
        // get default browsers
        $uniqueBrowserNames = array_unique($this->_coreConfig->getAttribute('browsers', 'core-config'));

        // overwrite browsers with project default
        $projectBrowserSettings = $this->_config->getAttribute('browsers');
        if (!empty($projectBrowserSettings)) {
            $uniqueBrowserNames = $projectBrowserSettings;
        }

        // overwrite browsers from phpunit paramter
        if ($this->_coreConfig->isParameterSet('--ss-browsers')) {
            $browsers = $this->_coreConfig->getParameter('--ss-browsers');
            $uniqueBrowserNames = array_map('trim', explode(',', $browsers));
        }

        return $uniqueBrowserNames;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getBrowsers()
    {
        return $this->_browsers;
    }

    public function getConfig()
    {
        return $this->_config;
    }

}
