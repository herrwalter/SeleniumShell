<?php


$tokens = token_get_all(file_get_contents(__dir__ . '/tokenClass.php'));
$opens = 0;
$closes = 0;
$className;

$tcr = new TestClassReader(__dir__ . '/tokenClass.php');
print '<pre>';
print_r( $tcr->getTestMethods() );
print_r( $tcr->getFileRapport() );






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