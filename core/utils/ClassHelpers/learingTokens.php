<?php


$tokens = token_get_all(file_get_contents(__dir__ . '/tokenClass.php'));
$opens = 0;
$closes = 0;
$className;

$tcr = new TestClassReader(__dir__ . '/tokenClass.php');
print '<pre>';
print_r( $tcr->getTestMethods() );
print_r( $tcr->getFileRapport() );


class TokenReader 
{
    private $_token;
    
    public function __construct( $token )
    {
        $this->_token = $token;
    }        
    /**
     * Checks if the token is a curly open
     * @return boolean
     */
    public function isCurlyOpen()
    {
        return is_string($this->_token) && $this->_token == '{';
    }
    /**
     * Checks if token is a curly close
     * @return boolean
     */
    public function isCurlyClose()
    {
        return is_string($this->_token) && $this->_token == '}';
    }
    /**
     * Checks if type is a Class
     * @return boolean
     */
    public function isClassDefinition()
    {
        return is_array($this->_token) && $this->_token[0] === T_CLASS;
    }
    
    public function isMethod()
    {
        return is_array($this->_token) && $this->_token[0] == T_FUNCTION;
    }
    /**
     * Checks if the type is a variable
     * @return boolean
     */
    public function isVariable()
    {
        return is_array($this->_token) && $this->_token[0] === T_VARIABLE;
    }
    
    public function isDocumentationBlock()
    {
        return is_array($this->_token) && $this->_token[0] === T_DOC_COMMENT;
    }
    /**
     * Gets the annotations from a doc block
     * @param string $doc
     * @return array with annotations
     */
    public function getAnnotations()
    {
        if( $this->isDocumentationBlock() ){
            preg_match_all('#@(.*?)\n#s', $this->_token[1], $annotations);
            $niceFormat = array();
            foreach( $annotations[1] as $annotation){
                $anno = explode(' ', $annotation);
                $niceFormat[] = array($anno[0] => $anno[1]);
            }
            return $niceFormat;
        }
        return;
    }    
    /**
     * Gets the string from the token;
     */
    public function getString()
    {
        if( is_array($this->_token) ){
            return $this->_token[1];
        }
        return $this->_token;
    }
    /**
     * Gets the line number of the token
     * @return mixed integer if accessible, false if not
     */
    public function getLineNumber()
    {
        $token = $this->_token;
        if( is_array( $this->_token )){
            return $this->_token[2];
        }
        return false;
    }
}
/**
 * Will keep track if your still in the samen closure 
 * while iterating file tokens.
 */
class TokenClosure
{
    /** @var TokenReader */
    protected $_tokenRdr;
    protected $_opens = 0;
    protected $_closes = 0;
    protected $_inClosure = false;
    protected $_done = false;
    protected $tracking = false;
    protected $track = '';
    protected $name = '';
    
    public function __construct( $name ){
        $this->name = $name;
    }
    
    public function track( $token )
    {
        if( $this->tracking ){
            $this->_tokenRdr = new TokenReader($token);
            $this->_addCurlyOpen();
            $this->_addCurlyClose();
            $this->_isInClosure();
            $this->_addTrack();
        }
    }
    
    protected function _addCurlyOpen()
    {
        if( $this->_tokenRdr->isCurlyOpen() ){
            $this->_opens++;
        }
    }
    
    protected function _addCurlyClose()
    {
        if( $this->_tokenRdr->isCurlyClose() ){
            $this->_closes++;
        }       
    }
    
    protected function _addTrack(){
        $this->track .= $this->_tokenRdr->getString();
    }
    
    protected function _isInClosure(){
        if( $this->_opens > 0 && $this->_opens > $this->_closes ){
            $this->_inClosure = true;
        } else if($this->_opens > 0 && $this->_opens == $this->_closes) {
            $this->_done = true;
            $this->_tracking = false;
            $this->_inClosure = false;
        }
    }
    
    public function startTracking(){
        $this->tracking = true;
    }
    
    public function inClosure(){
        return $this->_inClosure;
    }
    
    public function isDone(){
        return $this->_done;
    }
    
    public function getTrack(){
        return $this->track;
    }
    
    public function reset(){
        $this->_closes = 0;
        $this->_opens = 0;
        $this->track = '';
        $this->tracking = false;
        $this->_done = false;
    }
}

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
    
    protected function getPossibleAnnotations( $curIndex ){
        //look back 4 tokens max (doc/whitespace/public/whitespace)
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
            
            if( $class->inClosure() && $tokenRdr->isMethod( $token ) && $this->_isTestMethod($this->_tokens[$i+2]) ){
                $testNr++;
                $tests[$testNr] = array();
                $tests[$testNr]['annotations'] = $this->getPossibleAnnotations($i);
                $test->startTracking();
            }
            
            if( $test->isDone() ){
                $tests[$testNr]['test'] = $test->getTrack();
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
            if( is_array($this->_tokens[$currentIndex]) )
            {
                return $this->_tokens[$currentIndex];
            }
        }while($i > 0);
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
            if( $token[0] === T_VARIABLE){
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
            'nrOfTestMethods' => count($this->_nrOfTestsMethods),
            'methods' => $this->_methods,
            'testMethods' => $this->_testMethods,
            'classStart' => $this->_begin,
            'classEnd' => $this->_end,
        ); 
    }
}


/**
 * Creates a new test class based on an existing file.
 * Will change the given name of the class and will handle
 * some of the browser settings of the testclass. 
 */
class TestClassCreator
{
    public function __construct()
    {
        
    }
}


/**
 * Will recreate the class from tokens.
 * 
 * 
 * @param array $tokens the parsed tokens
 * @param integer $begin line number to start
 * @param integer $end line number to end
 * @param string $newClassName optional new class name.
 * @param boolean $classOnly will return only the class
 * @return string the reacreated class.
 */
function re_create_class( $tokens, $begin, $end, $newClassName = '', $classOnly = false ){
    $start_concat = false;
    $set_new_class_name = false;
    if( $newClassName !== '' && is_string($newClassName) ){
        $set_new_class_name = true;
    }
    $classRecreation = '';
    
    for( $i = 0; $i < count($tokens); $i++ ){
        $token = $tokens[$i];
        // if the line number equals the $begin and the token is a class set concat to start
        if( is_array($token) && $token[2] == $begin && $token[0] === T_CLASS ){
            $start_concat = true;
            
            // maybe set the new class name..
            if( $set_new_class_name ){
                if( $classOnly ){
                    $classRecreation = 'class ' . $newClassName;
                }
                else{
                    $classRecreation .= 'class ' . $newClassName;
                }
                $i = $i+2;
                continue;
            }
        }
        // if the line number is equals to the $end varibale.
        if( is_array($token) && $token[2] >= $end ){
            break;
        }
        // $start_concat will be true when we are at the line number that equals $begin
        if( $classOnly == true && $start_concat == true ){
            if( is_array($token) ){
                $classRecreation .= $token[1];
            } else if( is_string( $token ) ){
                $classRecreation .= $token;
            }
        }
        else if($classOnly == false ){
            if( is_array($token) ){
                $classRecreation .= $token[1];
            } else if( is_string( $token ) ){
                $classRecreation .= $token;
            }
        }
    }
    return $classRecreation;
}


function get_previous_token_containing_a_line_number( $tokens, $currentIndex )
{
    $i = $currentIndex;
    $offset = 0;
    do{
        $i--;
        $offset++;
        if( is_array($tokens[$i]) )
        {
            return $tokens[$i];
            break;
        }
    }while($i > 0);
    return false;
}
