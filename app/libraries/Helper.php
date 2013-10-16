<?php
/**
 * User: Rogier
 * Date: 15-10-13
 * Time: 20:27
 *
 */

class Helper {

    const SETTINGSFILE = '../app/config/settings.ini';


    // Function to write an ini file
    public static function writeIni($fileName, $iniArray)
    {
        $content = self::writeIniBuildOutputString($iniArray);
        if(false == file_put_contents($fileName, $content))
        {
            throw new Exception('Could not write to ini file '.$fileName);
        }
        return true;
    }


    // Function to build the output of writing an ini file
    // Copied from PEAR package Config_Lite
    // Find it at http://pear.php.net/package/Config_Lite/
    protected static function writeIniBuildOutputString($sectionsarray)
    {
        $content = '';
        $sections = '';
        $globals  = '';
        if (!empty($sectionsarray)) {
            // 2 loops to write `globals' on top, alternative: buffer
            foreach ($sectionsarray as $section => $item) {
                if (!is_array($item)) {
                    $value    = $item;
                    $globals .= $section . ' = ' . $value . "\n";
                }
            }
            $content .= $globals;
            foreach ($sectionsarray as $section => $item) {
                if (is_array($item)) {
                    $sections .= "\n"
                        . "[" . $section . "]" . "\n";
                    foreach ($item as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $arrkey => $arrvalue) {
                                $arrvalue  = $arrvalue;
                                $arrkey    = $key . '[' . $arrkey . ']';
                                $sections .= $arrkey . ' = ' . $arrvalue
                                    . "\n";
                            }
                        } else {
                            $value     = $value;
                            $sections .= $key . ' = ' . $value . "\n";
                        }
                    }
                }
            }
            $content .= $sections;
        }
        return $content;
    }
}



