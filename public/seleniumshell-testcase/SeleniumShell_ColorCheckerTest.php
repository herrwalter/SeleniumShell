<?php

abstract class SeleniumShell_ColorCheckerTest extends SeleniumShell_Test
{
    
    private function phpArrayToJavascriptArray($array)
    {
        return "['" .implode("','", $array). "']";
    }

    /**
     * @dataProvider urlProvider
     */
    public function testColorsOnPage($url)
    {
        $this->url($url);
        $allowedColors = $this->getAllowedColors();

        $this->execute(array(
            'script' => file_get_contents(COLORCHECKER_PATH) . PHP_EOL . 
                        'new ColorChecker('.$this->phpArrayToJavascriptArray($allowedColors).');' , 
            'args' => array()));
        
        while(true ){
            if( count($this->selectElementsByCssSelector('#progressbar')) == 1 ){
                if( $this->byCssSelector('#progressbar')->text() == '100%'){
                    break;
                }
            }
            sleep(1);
        }
        $wrongColors = $this->selectElementsByCssSelector('.wrongcolor');
        
        $wrongColorsList = PHP_EOL. 'The following wrong colors are found: ' . PHP_EOL;
        foreach($wrongColors as $color ){
            $wrongColorsList .= "\t -".  $color->text() . PHP_EOL;
        }
        
        $wrongColorsList .= 'checking the following url: ' . $url;
            
        $this->assertEquals(0, count($wrongColors), $wrongColorsList);
    }

    /**
     * Provide the urls you want to test against.
     * Keep format as PHPUnit dataProvider
     */
    abstract public function urlProvider();

    /**
     * Return your allowed colors in an Array. 
     * It can be Hex, RGB or shorthand HEX
     * 
     * array('#fff','#000','#BADA55','rgb(128,128,128)');
     * 
     * @return array
     */
    abstract public function getAllowedColors();
}
