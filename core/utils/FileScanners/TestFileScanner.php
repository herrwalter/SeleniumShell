<?php



class TestFileScanner extends FileScanner
{
    
    protected function validateFile( $file )
    {
        $filename = pathinfo($file, PATHINFO_FILENAME);
        return substr_compare($filename, 'Test', -4) === 0;
    }
    
}
