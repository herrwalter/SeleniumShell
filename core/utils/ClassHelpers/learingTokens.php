<?php


$tokens = token_get_all(file_get_contents(__dir__ . '/tokenClass.php'));
$opens = 0;
$closes = 0;
$className;


class CreateNewClass
{
    public function __construct( $file, $browser )
    {
        
    }
    
    
    
}

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
    public function getAnnotations( $doc )
    {
        $annotations = array();
        preg_match_all('#@(.*?)\n#s', $doc, $annotations);
        return $annotations;
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
    private $_fileTokens;
    private $_nrOfClasses = 0;
    private $_nrOfTestsMethods = 0;
    private $_nrOfMethods = 0;
    private $_className = '';
    private $_counting = false;
    private $_curlyOpens = 0;
    private $_curlyCloses = 0;
    
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
        $this->_fileTokens = token_get_all($this->_file);
    }
    
    private function _isClassClosed()
    {
        return $this->_counting && $this->_curlyOpens == $this->_curlyCloses;
    }
    
    private function _addCurlyOpen()
    {
        if( $this->_counting ){
            $this->_curlyOpens ++;
        }
    }
    
    private function _addCurlyClose()
    {
        if( $this->_counting ){
            $this->_curlyOpens++;
        }
    }
    protected function _readFile()
    {
        for($i = 0; $i < count($this->_tokens); $i++){
            $token = $this->_tokens[$i];
            $tokenRdr = new TokenReader($token);
            if( $tokenRdr->isCurlyOpen() ){
                $this->_addCurlyOpen();
            }
            if( $tokenRdr->isCurlyClose() ){
                $this->_addCurlyClose();
            }    
            if( $tokenRdr->isClassDefinition() ){
                $this->_nrOfClasses ++;
                $this->_className = $tokens[$i+2][1];
                $this->_counting = true;
            }
            // if the token is a curly close and the counted curly closes and opens are equal
            if( $opens > 0 && $opens == $closes ){
                $arr = get_previous_token_containing_a_line_number($tokens, $i); // get previous token with array to get the closing line.
                $end = ($arr[2] + 1);
                var_dump(re_create_class($tokens, $begin, $end, 'wouter'.$className));
                break;
            }
        }
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
