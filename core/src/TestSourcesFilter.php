<?php

class TestSourcesFilter
{

    /** @var TestSources */
    protected $sources;

    public function __construct(TestSources $testSources)
    {
        $this->sources = $testSources;
    }

    public function filterSourcesByClassName($className)
    {
        $files = array();
        foreach( $this->sources->getFiles() as $file )
        {
            $end = $className . '.php';
            if(strpos($file, $end) !== false ){
                $files[] = $file;
            }
        }
        return new TestSources($files);
    }

}
