<?php
/**
 * This file contains the main settings for PrettyForum like database credentials
 *
 */

$iniInfo = parse_ini_file('settings.ini', true);

return array(

    'database' => array(
        'host' => $iniInfo['database']['host'],
        'username' => $iniInfo['database']['username'],
        'password' => $iniInfo['database']['password']
    ),

    'email' => array(
        'from' => $iniInfo['email']['from'],
        'name' => $iniInfo['email']['name']
    ),


    'setup' => array(
        'status' => $iniInfo['setup']['status'],
        'date' => $iniInfo['setup']['date']
    )


);

