<?php



class TestFileScanner extends FileScanner
{
    
    private function _isTestFile( $file )
    {
        $check = pathinfo($file);
        $filename = $check['filename'];
        return strpos($file, 'Test') !== false;
    }
    
    public function getFilesInOneDimensionalArray()
    {
        $testFiles = array();
        foreach( $this->_files as $dir => $files )
        {
            foreach( $files as $file )
            {
                var_dump( $file );
                var_dump( $this->_isTestFile($file));
                if( $this->_isTestFile($file) ){
                    $testFiles[] = $dir.$file;
                }
            }
        }
        return $testFiles;
    }
}