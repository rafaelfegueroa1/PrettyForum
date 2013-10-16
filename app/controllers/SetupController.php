<?php


// Controller controlling all setup related actions
class SetupController extends BaseController {


    public function __construct()
    {

        // Run this filter before Install
        $this->beforeFilter(function()
        {
            // Check if settings file exists
            if(file_exists(Helper::SETTINGSFILE))
            {
                // Read settings
                $settings = parse_ini_file(Helper::SETTINGSFILE, true);
                // Check if install is already done, if true, return a 404 not found.
                if(isset($settings['setup']['status']) && $settings['setup']['status'] == 'complete')
                {
                    App::abort(404, 'Page not found');
                }
            }
        },
        array('except' => array('getUninstall', 'postUninstall')));

        // Run CSRF filter before uninstall post route
        $this->beforeFilter('csrf', array('only' => array('postUninstall')));

    }


    // Start of install
    // Asks for basic database information
    public function getIndex()
    {
        return View::make('install.install');
    }

    // Handle all settings the user just posted
    public function postIndex()
    {
        // Retrieve input
        $input = Input::get();

        // Create an array we will write to an ini
        $databaseInfo = array(
            'database' => array(
                'host' => $input['databaseHost'],
                'username' => $input['databaseUsername'],
                'password' => $input['databasePassword'],
            ),
            'email' => array(
                'from' => $input['emailFrom'],
                'name' => $input['emailName'],
            ),

            'appSettings' => array(
                'defaultTitle' => $input['defaultTitle'],
                'forumName' => $input['forumName'],
            ),

            'setup' => array(
                'status' => 'complete',
                'date' => date('d-m-Y H:i'),
                'uninstallKey' => $input['uninstallKey'],
            )
        );
        // Write said ini
        Helper::writeIni(Helper::SETTINGSFILE, $databaseInfo);

        // Create a new PDO object to test the credentials and then create the database
        $db = new PDO('mysql:host='.$input['databaseHost'], $input['databaseUsername'], $input['databasePassword']);
        $db->query('CREATE DATABASE IF NOT EXISTS '. Config::get('database.connections.mysql.database'));

        // Set config for this request so we can immediately start using Laravel's classes that utilize the database
        Config::set('database.connections.mysql.host', $input['databaseHost']);
        Config::set('database.connections.mysql.username', $input['databaseUsername']);
        Config::set('database.connections.mysql.password', $input['databasePassword']);

        // Write default layout to views folder
        file_put_contents(Helper::VIEWSFOLDER.'layout.blade.php', file_get_contents(Helper::DEFAULTFOLDER.'defaultLayout.blade.php'));

        // Create all necessary tables
        Helper::createInstallTables();









        return View::make('install.installSuccess');
    }


    // Return uninstall view
    public function getUninstall()
    {
        $installed = 0;
        // Check if PrettyForum is installed
        if(file_exists(Helper::SETTINGSFILE))
        {
            $settings = parse_ini_file(Helper::SETTINGSFILE, true);
            if(isset($settings['setup']['status']) && $settings['setup']['status'] == 'complete')
            {
                $installed = 1;
            }
        }
        return View::make('install.uninstall')->with('installed', $installed);
    }

    // Uninstall PrettyForum (removes settings only)
    public function postUninstall()
    {
        if(file_exists(Helper::SETTINGSFILE))
        {
            // Read settings
            $settings = parse_ini_file(Helper::SETTINGSFILE, true);
            // Check if install is already done, if true, return a 404 not found.
            if(!isset($settings['setup']['uninstallKey']))
            {
                return Redirect::action('SetupController@getUninstall');
            }
        }

        // Action is CSRF protected to counter unwanted uninstalls
        $uninstallKey = Input::get('uninstallKey');
        if($uninstallKey !== Config::get('settings.setup.uninstallKey'))
        {
            Session::flash('wrongUninstallKey', 1);
            return Redirect::action('SetupController@getUninstall');
        }
        // Remove settings file
        unlink(Helper::SETTINGSFILE);
        // Remove chosen layout from views
        unlink(Helper::VIEWSFOLDER.'layout.blade.php');
        Helper::removeFilesFromFolder(Helper::LAYOUTFOLDER);


        return View::make('install.uninstallSuccess');


    }


}