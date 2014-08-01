<?php

class SourceRewriter
{

    /** @var Project */
    protected $_project;
    /** @var FileScanner */
    protected $_fileScanner;
    protected $_sourcePath;
    protected $_sources;
    /** @var TestClassRecreator */
    protected $_tcr;
    protected $_generatedFiles = array();

    /**
     * @param Project $project
     * @param FileScanner $fileScanner
     * @param TestClassRecreator $tcr
     * @param type $sourcePath
     * @param type $className if this classname is found, it will only return this class name.
     * 
     */
    public function __construct(Project $project, FileScanner $fileScanner, TestClassRecreator $tcr, $sourcePath)
    {
        var_dump($sourcePath);
        $this->_project = $project;
        $this->_fileScanner = $fileScanner;
        $this->_tcr = $tcr;
        $this->_sourcePath = $sourcePath;
        
        $this->setSources();
        $this->_generateTestFiles();
    }
    
    public function getSources()
    {
        return new TestSources($this->_generatedFiles);
    }
    
    protected function setSources()
    {
        $this->_fileScanner->_setDir($this->_sourcePath);
        $this->_sources = $this->_fileScanner->getFilesInOneDimensionalArray();
    }
    
    private function _generateTestFiles()
    {
        foreach ($this->_sources as $file) {
            $this->createTestFileForEveryBrowser($file);
        }
    }

    protected function createTestFileForEveryBrowser( $file )
    {
        $this->_tcr->setTestClassFile($file);
        $this->_tcr->setSavePath(GENERATED_TESTSUITES_PATH . Session::getId() . DIRECTORY_SEPARATOR . $this->_project->getName() . DIRECTORY_SEPARATOR);
        $this->_tcr->setProjectName($this->_project->getName());
        foreach ($this->_project->getBrowsers() as $browser) {
            $this->_generatedFiles[] = $this->_tcr->createFileForBrowser($browser);
        }
    }

}
