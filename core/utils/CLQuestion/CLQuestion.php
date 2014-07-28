<?php

abstract class CLQuestion
{

    protected $_awnser;
    protected $_question;

    public function __construct($question)
    {
        $this->_question = $question;
    }

    public function askQuestion()
    {
        echo PHP_EOL . $this->_question . PHP_EOL;
        $this->waitForAwnser();
    }

    protected function waitForAwnser()
    {
        while (true) {
            $response = strtolower(trim(fgets(STDIN)));
            if ($this->validateResponse($response)) {
                $this->_awnser = $response;
                break;
            } else {
                $this->tryAgainMessage();
                continue;
            } 
        }
    }
    /**
     * Validates response
     * @return boolean Description
     */
    protected abstract function validateResponse($response);
    
    /**
     * Outputs message on unvalid response
     */
    protected abstract function tryAgainMessage();

    public function getAwnser()
    {
        return $this->_awnser;
    }
    
    public function setQuestion($question)
    {
        $this->_question = $question;
    }
    
    public function getQuestion()
    {
        return $this->_question;
    }

}
