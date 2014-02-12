<?php
/**
 * The TestClassReader can read a file based on a SeleniumShell_Test
 * You may ask conditions of the state of this testclass.
 */
class TestClassReader
{
    /**
     * @var string _file File to read
     */
    private $_file;
    private $_tokens;
    private $_nrOfClasses = 0;
    private $_nrOfTestsMethods = 0;
    private $_nrOfMethods = 0;
    private $_methods = array();
    private $_testMethods = array();
    private $_invalidTestMethods = array();
    private $_className = '';
    private $_classContents = '';
    private $_counting = false;
    private $_curlyOpens = 0;
    private $_curlyCloses = 0;
    private $_begin;
    private $_end;
    
    public function __construct( $file )
    {
        $this->_setFile($file);
        $this->_setFileTokens();
        $this->_readFile();
    }
    
         
    protected function _setFile($file)
    {
        $this->_file = $file;
    }
    /**
     * Sets the tokens of the give file.
     */
    protected function _setFileTokens()
    {
        $this->_tokens = token_get_all(file_get_contents($this->_file));
    }
    
    private function _isTestMethod($token)
    {
        $method = $token;
        if( is_array($token) ){
            $method = $token[1];
        }
        return substr($method, 0, 4) == 'test';
    }
    
    private function _isClassClosed()
    {
        return $this->_counting && $this->_curlyOpens == $this->_curlyCloses;
    }
    
    private function _setBegin()
    {
        if( !$this->_begin && $this->_counting ){
            
        }
    }
    
    private function _addCurlyOpens()
    {
        if( $this->_counting ){
            $this->_setBegin();
            $this->_curlyOpens ++;
        }
    }
    
    private function _addCurlyCloses()
    {
        if( $this->_counting ){
            $this->_setEnd();
            $this->_curlyOpens++;
        }
    }
    
    protected function _getPossibleAnnotations( $curIndex ){
        //look back 4 tokens max (doc/whitespace/public/whitespace)
        // when it is a test method.. 
        if( $this->_tokens[$curIndex -1][0] == T_WHITESPACE &&
            $this->_tokens[$curIndex -2][0] == T_PUBLIC &&
            $this->_tokens[$curIndex -3][0] == T_WHITESPACE &&
            $this->_tokens[$curIndex -4][0] == T_DOC_COMMENT ){
            $tokenRdr = new TokenReader($this->_tokens[$curIndex -4]);
            return $tokenRdr->getAnnotations();
        }
        return false;
    }
    
    public function getTestMethods()
    {
        $class = new TokenClosure('class');
        $test = new TokenClosure('test');
        $tests = array();
        $testNr = 0;
        
        for( $i = 0; $i < count($this->_tokens); $i ++ ){
            $token = $this->_tokens[$i];
            $tokenRdr = new TokenReader($token);
            
            
            if( $tokenRdr->isClassDefinition() ){
                $class->startTracking();
            }
            $class->track($token);
            
            if( $class->inClosure() && $tokenRdr->isMethod() && $this->_isTestMethod($this->_tokens[$i+2]) ){
                $testNr++;
                $tests[$testNr] = array();
                $tests[$testNr]['annotations'] = $this->_getPossibleAnnotations($i);
                $test->startTracking();
            }
            
            if( $test->isDone() ){
                $tests[$testNr]['test'] = 'public ' . $test->getTrack();
                $test->reset();
            }
            
            $test->track($token);
        }
        
        return $tests;
    }
    
    /**
     * 
     * @param integer $currentIndex current index of tokens
     * @return boolean
     */
    protected function _getLineNumber( $currentIndex )
    {
        $rdr = new TokenReader($this->_tokens[$currentIndex]);
        if( $rdr->getLineNumber() ){
            return $rdr->getLineNumber();
        }
        do{
            $currentIndex--;
            if( is_array($this->_tokens[$currentIndex]) ){
                return $this->_tokens[$currentIndex];
            }
        } while($i > 0);
        return false;
    }
    
    protected function _readFile()
    {
        for($i = 0; $i < count($this->_tokens); $i++){
            $token = $this->_tokens[$i];
            $tokenRdr = new TokenReader($token);

            if( $tokenRdr->isCurlyOpen() ){
                $this->_addCurlyOpens();
            }
            
            if( $tokenRdr->isCurlyClose() ){
                $this->_addCurlyCloses();
            }    
            
            if( $tokenRdr->isClassDefinition() ){
                $this->_nrOfClasses ++;
                $this->_className = $this->_tokens[$i+2][1];
                $this->_counting = true;
            }
            if( $tokenRdr->isMethod() ){
                $rdr = new TokenReader($this->_tokens[$i + 2]);
                if( $this->_isTestMethod($rdr->getString()) ){
                    $this->_testMethods[] = $this->_getMethod($i);
                }
                else{
                    $this->_methods[] = $this->_getMethod($i);
                }
                
            }
            // if the token is a curly close and the counted curly closes and opens are equal
            if( $this->_isClassClosed() ){
                $this->_counting = false;
                $end = $this->_getLineNumber($i); // get previous token with array to get the closing line.
                $this->_end = $end; // set the end of the class
            }
        }
    }
    
    protected function _getMethod($currentIndex)
    {
        $opens = 0;
        $closes = 0;
        $method = '';
        // we will start 2 tokens back to get the 'public', 'protected' or 'private; string
        for( $i = ($currentIndex - 2); $i < count($this->_tokens); $i++ )
        {
            $token = $this->_tokens[$i];
            $tokenRdr = new TokenReader($token);
            $method .= $tokenRdr->getString(); // get each string of the token.
            if( $tokenRdr->isCurlyOpen() ){
                $opens ++;
            }
            if( $tokenRdr->isCurlyClose() ){
                $closes ++;
            }
            if( $opens > 0 && $opens == $closes ){
                break;
            }
        }
        return $method;
    }
    
    public function getFileRapport()
    {
        return array(
            'classes' => $this->_nrOfClasses,
            'nrOfMethods' => count($this->_methods),
            'nrOfTestMethods' => count($this->_testsMethods),
            'methods' => $this->_methods,
            'testMethods' => $this->_testMethods,
            'classStart' => $this->_begin,
            'classEnd' => $this->_end,
        ); 
    }
}