<?php



class TestFileScanner extends FileScanner
{
    
    private function _isTestFile( $file )
    {
        $check = pathinfo($file);
        $filename = $check['filename'];
        return substr_compare($filename, 'Test', -4) === 0;
    }
    
    public function getFilesInOneDimensionalArray()
    {
        $testFiles = array();
        foreach( $this->_files as $dir => $files )
        {
            foreach( $files as $file )
            {
                if( $this->_isTestFile($file) ){
                    $testFiles[] = $dir.$file;
                }
            }
        }
        return $testFiles;
    }
}
