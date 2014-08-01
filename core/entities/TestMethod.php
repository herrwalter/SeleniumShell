<?php

class TestMethod
{

    /** @var AnnotationReader */
    private $_annotations;
    private $_method;
    private $_stripMethod = false;

    public function setAnnotations($annotations)
    {
        $this->_annotations = new AnnotationReader($annotations);
    }

    /**
     * @return AnnotationReader
     */
    public function getAnnotations()
    {
        return $this->_annotations;
    }

    public function hasAnnotations()
    {
        return $this->_annotations->hasAnnotations();
    }

    public function setMethod($method)
    {
        $this->_method = $method;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getName()
    {
        preg_match('/public function (.*?)\(.*?\)/', $this->_method, $matches);
        return $matches[1];
    }

    public function stripMethod()
    {
        $this->_stripMethod = true;
    }

    public function getStripMethodState()
    {
        return $this->_stripMethod;
    }

}
