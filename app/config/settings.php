<?php
/**
 * This file contains the main settings for PrettyForum like database credentials
 *
 */

try
{
    //$iniInfo =
    return parse_ini_file('settings.ini', true);
}
catch (Exception $e)
{
    throw new Exception('Could not open settings.ini');
}

/*
return array(

    'database' => array(
        'host' => $iniInfo['database']['host'],
        'username' => $iniInfo['database']['username'],
        'password' => $iniInfo['database']['password'],
    ),

    'email' => array(
        'from' => $iniInfo['email']['from'],
        'name' => $iniInfo['email']['name'],
    ),

    'appSettings' => array(
        'defaultTitle' => $iniInfo['appSettings']['defaultTitle'],
        'forumName' => $iniInfo['appSettings']['forumName'],
        'forumTimezone' => $iniInfo['appSettings']['forumTimezone']
    ),

    'setup' => array(
        'status' => $iniInfo['setup']['status'],
        'date' => $iniInfo['setup']['date'],
        'uninstallKey' => $iniInfo['setup']['uninstallKey'],
    ),


);
*/
