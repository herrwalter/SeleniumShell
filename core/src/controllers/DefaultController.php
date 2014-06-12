<?php

class DefaultController extends Controller
{

    protected $_validateArgumentCount = false;

    public function run()
    {
        new PathCLQuestion('What is your name?');
        //new MultipleChoiseCLQuestion('Where do you live', array('land', 'water'));
        
    }

    public function getMandatoryArguments()
    {
        return array('project');
    }

    public function getHelpDescription()
    {
        return 'running tests';
    }

}
