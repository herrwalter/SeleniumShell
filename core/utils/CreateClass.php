<?php



class CreateClass
{
    protected $_className;
    protected $_file = '<?php ';
    protected $_includes;
    protected $_variables ='';
    protected $_header;
    protected $_methods;
    protected $_footer = '}';
    
    public function __construct( $className )
    {
        $this->_setClassName($className);
                
    }
    
    protected function _setClassName( $className )
    {
        $this->_className = $className;
    }
    
    protected function _createHeader()
    {
        $this->_header = 'class ' . $this->_className . '{';
    }
    
    public function setNewClassReference( $className, $path )
    {
        $this->_variables .= '/** @var '.$className.' */';
        $this->_variables .= 'public $'.strtolower($className).' = new ' . $className . '();'; 
        $this->_includes .= 'require_once("'.$path.'")';
    }
    
    public function writeFile()
    {
        $this->_file .= $this->_includes;
        $this->_file .= $this->_header;
        $this->_file .= $this->_variables;
        $this->_file .= $this->_methods;
        $this->_file .= $this->_footer;
        file_put_contents(GENERATED . $this->_className . '.php', $this->_file);
    }
    
            
}