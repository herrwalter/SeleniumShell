<?php



class InstallController extends Controller 
{
    
    public function getMandatoryArguments()
    {
        return array();
    }

    public function run()
    {
        $img = new Image('C:\SeleniumTests\SeleniumShell\generated\screenshots\1402485714\chrometestFooterLinks.jpg');
        $img2 = new Image('C:\SeleniumTests\SeleniumShell\generated\screenshots\1402485298\chrometestFooterLinks.jpg');
        $compare = new ImageCompare($img, $img2);
        if( $compare->getOffsetDimensions() !== null ){
            $slicer = new ImageSlicer($img2, $compare->getOffsetDimensions());
            imagejpeg($slicer->getSlice(), 'C:\SeleniumTests\SeleniumShell\generated\screenshots\seriousdif.jpg', 100 );
        }
    }

    public static function getHelpDescription()
    {
        return 'TBI: For installing selenium shell';
    }

}