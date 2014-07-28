<?php

class DeleteGeneratedFilesController extends Controller
{
    public function run()
    {
        $this->validateArguments();
        $this->handleEveryArgument();
    }
    
    protected function validateArguments()
    {
        if( count($this->getArguments()) === 0 ){
            throw new ErrorException('delete-generated-files command expects at least one of these optional arguments: ' . PHP_EOL . print_r($this->getOptionalArguments(), true) );
        }
    }
    
    protected function handleEveryArgument()
    {
        $arguments = $this->getArguments();
        foreach($arguments as $argument => $set){
            $this->deleteFilesByArgument($argument);
            $this->createDirByArgument($argument);
        }
    }
    
    protected function createDirByArgument($argument)
    {
        switch ($argument){
            case '--all':
                mkdir(GENERATED_DEBUG_PATH);
                mkdir(GENERATED_RESULTS_PATH);
                mkdir(GENERATED_SCREENSHOTS_PATH);
                mkdir(GENERATED_SETUP_BEFORE_PROJECT_PATH);
                mkdir(GENERATED_TESTSUITES_PATH);
                break;
            case '--screenshots':
                mkdir(GENERATED_SCREENSHOTS_PATH);
                break;
            case '--results':
                mkdir(GENERATED_RESULTS_PATH);
                break;
            case '--debug':
                mkdir(GENERATED_DEBUG_PATH);
                break;
            case '--setup-before-project':
            case '--sbp':
                mkdir(GENERATED_SETUP_BEFORE_PROJECT_PATH);
                break;
            case '--testsuites':
                mkdir(GENERATED_TESTSUITES_PATH);
                break;
            default:
                throw new Error('No case implemented for creating dir for argument ' . $argument);
        }
    }
    
    protected function deleteFilesByArgument($argument)
    {
        switch ($argument){
            case '--all':
                HelperFunctions::deleteTree(GENERATED_DEBUG_PATH);
                HelperFunctions::deleteTree(GENERATED_RESULTS_PATH);
                HelperFunctions::deleteTree(GENERATED_SCREENSHOTS_PATH);
                HelperFunctions::deleteTree(GENERATED_SETUP_BEFORE_PROJECT_PATH);
                HelperFunctions::deleteTree(GENERATED_TESTSUITES_PATH);
                break;
            case '--screenshots':
                HelperFunctions::deleteTree(GENERATED_SCREENSHOTS_PATH);
                break;
            case '--results':
                HelperFunctions::deleteTree(GENERATED_RESULTS_PATH);
                break;
            case '--debug':
                HelperFunctions::deleteTree(GENERATED_DEBUG_PATH);
                break;
            case '--setup-before-project':
            case '--sbp':
                HelperFunctions::deleteTree(GENERATED_SETUP_BEFORE_PROJECT_PATH);
                break;
            case '--testsuites':
                HelperFunctions::deleteTree(GENERATED_TESTSUITES_PATH);
                break;
            default:
                throw new Error('No case implemented for deleting tree for argument ' . $argument);
        }
    }
    
    public function getOptionalArguments()
    {
        return array(
            '--all',
            '--screenshots',
            '--results',
            '--debug',
            '--setup-before-project',
            '--sbp',
            '--testsuites'
        );
    }
    
    
}