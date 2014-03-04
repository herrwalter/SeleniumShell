<?php


class SeleniumShell_CSVReader {

    private $_filename;
    private $_delimiter;
    private $_csvData = array();

    public function __construct($filename = '', $delimiter = ';') {
        $this->_setFilename($filename);
        $this->_delimiter = $delimiter;
        $this->_setCsvData();
    }

    protected function _setFilename($filename) {
        if (!file_exists($filename) || is_readable($filename)) {
            $this->_filename = $filename;
        } else {
            throw new ErrorException( $filename . ' does not exists or is not readable');
        }
    }

    protected function _setCsvData() {
        $header = false;
        $data = array();
        if( ($handle = fopen($this->_filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $this->_delimiter)) !== false) {
                if(!$header){
                    $header = $row;
                } else {
                    $this->_csvData[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
    
    public function getCsvData(){
        return $this->_csvData;
    }
    
    public function getHeaders(){
        return array_keys($this->_csvData[0]);
    }

}