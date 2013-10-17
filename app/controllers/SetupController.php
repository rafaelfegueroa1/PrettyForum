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
        $info = array(
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
        Helper::writeIni(Helper::SETTINGSFILE, $info);

        // Create a new PDO object to test the credentials and then create the database
        $db = new PDO('mysql:host='.$input['databaseHost'], $input['databaseUsername'], $input['databasePassword']);
        $db->query('CREATE DATABASE IF NOT EXISTS '. Config::get('database.connections.mysql.database'));

        // Set config for this request so we can immediately start using Laravel's classes that utilize the database
        Config::set('database.connections.mysql.host', $input['databaseHost']);
        Config::set('database.connections.mysql.username', $input['databaseUsername']);
        Config::set('database.connections.mysql.password', $input['databasePassword']);

        // Copy default views to views/views folder
        Helper::copyFilesRecursively(Helper::TEMPLATESFOLDER.'/layouts/default', Helper::VIEWSFOLDER);
        // Copy default assets to public/assets
        Helper::copyFilesRecursively(Helper::TEMPLATESFOLDER.'/assets/default', Helper::PUBLICFOLDER.'/assets');


        // Create all necessary tables
        Helper::createInstallTables();

        $section = new Section;
        $section->title = 'General';
        $section->save();

        $category = new Category;
        $category->title = 'Lounge';
        $category->description = 'Just talk about whatever is on your mind...';
        $category->parent_section = 1;
        $category->save();

        $user = new User;
        $user->username = 'Admin';
        $user->password = 'test';
        $user->email = 'Admin@admin.com';
        $user->save();

        $topic = new Topic;
        $topic->title = 'Welcome!';
        $topic->body = 'Welcome to your forum!';
        $topic->user_id = $user->id;
        $topic->category_id = $category->id;
        $topic->save();

        $reply = new Reply;
        $reply->body = 'This is a test reply';
        $reply->topic_id = $topic->id;
        $reply->user_id = $user->id;
        $reply->category_id = $category->id;
        $reply->save();

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

    // Uninstall PrettyForum
    public function postUninstall()
    {

        if(file_exists(Helper::SETTINGSFILE))
        {
            // Read settings
            $settings = parse_ini_file(Helper::SETTINGSFILE, true);
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

        // Remove all views from the views/views folder
        Helper::deleteFolderAndContents(Helper::VIEWSFOLDER);
        Helper::deleteFolderAndContents(Helper::PUBLICFOLDER.'/assets');


        return View::make('install.uninstallSuccess');

    }


}