<?php



class TestFileScanner extends FileScanner
{
    
    private function _isTestFile( $file )
    {
        $check = pathinfo($file);
        $filename = $check['filename'];
        return strpos($filename, 'Test') > 0;
    }
    
    public function getFilesInOneDimensionalArray()
    {
        $files = array();
        foreach( $this->_files as $dir => $files )
        {
            foreach( $files as $file )
            {
                if( $this->_isTestFile($file) ){
                    $files[] = $dir.$file;
                }
            }
        }
        return $files;
    }
}