<?php


class MultipleChoiseCLQuestion extends CLQuestion {
    
    protected $_choises;
    
    /**
     * @param string $question
     * @param array $choises with choises
     */
    public function __construct($question, $choises)
    {
        $this->_choises = $choises;
        parent::__construct($question);
    }
    
    public function validateResponse($response)
    {
        if (in_array($response, $this->_choises)) {
            // options given and found in options
            return true;
        } 
        return false;
    }

    protected function tryAgainMessage()
    {
        echo PHP_EOL . 'Please use one of these options: ';
        echo PHP_EOL . " " . implode(PHP_EOL . " ", $this->_choises);
        echo PHP_EOL;
    }

}
