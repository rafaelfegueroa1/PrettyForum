<?php

class InstallController extends BaseController {



    public function __construct()
    {
        // Check if settings file exists
        if(file_exists(Helper::SETTINGSFILE))
        {
            // Read settings
            $settings = parse_ini_file(Helper::SETTINGSFILE);
            // Check if install is already done, if true, return a 404 not found.
            if(isset($settings['setup']['status']) && $settings['setup']['status'] == 'complete')
            {
                App::abort(404, 'Page not found');
            }
        }
    }


    // Start of install
    // Asks for basic database information
    public function getIndex()
    {
        return View::make('install.index');
    }

    // Handle database information
    public function postIndex()
    {
        // Retrieve input
        $input = Input::get();

        // Create an array we will write to an ini
        $databaseInfo = array(
            'database' => array(
                'host' => $input['databaseHost'],
                'username' => $input['databaseUsername'],
                'password' => $input['databasePassword']
            ),
            'email' => array(
                'from' => $input['emailFrom'],
                'name' => $input['emailName']
            ),


            'setup' => array(
                'status' => 'complete',
                'date' => date('d-m-Y H:i')
            )
        );
        // Write said ini
        Helper::writeIni(Helper::SETTINGSFILE, $databaseInfo);

        // Create a new PDO object to test the credentials and then create the database
        $db = new PDO('mysql:host='.$input['databaseHost'], $input['databaseUsername'], $input['databasePassword']);
        $db->query('CREATE DATABASE IF NOT EXISTS '. Config::get('database.connections.mysql.database'));
        return View::make('forum.home');
    }


}