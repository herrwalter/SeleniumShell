<?php



class Project
{
    private $_name;
    
    private $_path;
    
    private $_testClassNames;
    
    private $_config;
    
    public function __construct( $projectName )
    {
        $this->_setProjectName($projectName);
        $this->_setProjectPath();
        $this->_setProjectConfig();
        $this->_setProjectsTestClassNames();
    }
    
    private function _setProjectName( $projectName )
    {
        $this->_name = $projectName;
    }
    
    private function _setProjectPath()
    {
        $this->_path = PROJECTS_FOLDER . '\\' . $this->_name;;
    }
    
    public function getProjectName()
    {
       return $this->_name;
    }
    
    public function getProjectPath()
    {
        return $this->_path;
    }
    
    public function getProjectsTestClassNames()
    {
        return $this->_testClassNames;
    }
    private function _setProjectConfig()
    {
        $this->_config = new ConfigHandler($this->_path . '\config\project.ini');
    }
    
    private function _setProjectsTestClassNames()
    {
        // first include the files
        $testFiles = new TestFileIncluder($this->_path . '\testsuits');
        $testFiles->includeFiles();
        
        
        // then get the files that got included
        $includedFiles = $testFiles->getInlcudedFiles();
        $refinedFiles = false;
        
        /**
         * TODO: come up with a design to implement changes by the annotations
         */
        
        /**
         * De bedoeling is dat ik elke testfile inlees en op basis
         * van elke annotation de juist testfile ga creeëren.
         * 
         * Er zullen verschillende annotation codes zijn die invloed hebben
         * op hoe de testclass moet worden geïnterpreteert
         * 
         * De logica van de annotations moet makkelijk beheerbaar zijn.
         * 
         * Bij een solo-run stop zou alles moeten stoppen en alleen deze
         * testmethode geïsoleerd worden uit de class. De rest van de classes
         * horen er niet meer bij. Ook eerder geïnstatiëerde classes niet.
         * 
         * Het framework moet ook de testen in meerdere browsers draaien.
         * Dit moet voor elke test anders ingesteld kunnen met een annotation.
         * De default waarden moeten uit de configfile komen en ook overschrijf
         * baar zijn voor een complete testclass.
         * 
         * De data beheerbaarheid moet toepasbaar kunnen zijn voor meerder omgevingen
         * (development, testing, accpetance en production).
         * 
         * De solo-run annotation rule:
         *  Kan toegepast worden op elke testmethode.
         *  Wanneer toegepast moeten alle overige testmethoden verwijderd worden.
         *  normale methoden moeten wel blijven bestaan. Ook eventuele variablen.
         *  Eerst gevonden solo-run annotation wordt direct toegepast.
         *  Voor elke browser test moet een nieuwe file komen. 
         * 
         */
        foreach( $includedFiles as $key => $file ){
            $tcr = new TestClassReader($file);
            $tests = $tcr->getTestMethods();
            
            $solorun = false;
            foreach( $tests as $testMethod ){
                $annotationsOnTestMethod = new AnnotationReader($testMethod);
                if( $annotationsOnTestMethod->hasSoloRun() ){
                    new TestClassRecreator($file);
                    $refinedFiles = array($file);
                    break;
                }
            }
        }
        
        if( $refinedFiles ){
            $includedFiles = $refinedFiles;
        }
        
        // get their classnames
        $classInstantiator =  new ClassInstantiator($includedFiles);
        $this->_testClassNames = $classInstantiator->getClassNames();
        
        
        
    }
    
    
    
}