<?php
/**
 * User: Rogier
 * Date: 15-10-13
 * Time: 20:27
 *
 */

class Helper {

    const SETTINGSFILE = '../app/config/settings.ini';
    const LAYOUTFOLDER = '../app/views/layouts/';
    const VIEWSFOLDER = '../app/views/layouts';
    const TEMPLATESFOLDER = '../app/templates/';
    const PUBLICFOLDER = '../public';

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


    public static function deleteFolderAndContents($dir)
    {
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($it,
            RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
    }

    // Helper to copy a folder's contents to destination
    public static function copyFilesRecursively($src, $dst) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    mkdir($dst . '/' . $file);
                    self::copyFilesRecursively($src . '/' . $file, $dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }


    // Shorten given string to $maxLength
    public static function shorten($string, $maxLength = 25)
    {
        if(strlen($string) >= $maxLength)
        {
            $string  = substr($string, 0, $maxLength);
        }
        return $string;
    }



    // Forum helper
    public static function getDateFormatted($date)
    {
        if(date('Ymd') == date('Ymd', strtotime($date)))
        {
            return '<span title="'.date('j-n-Y', strtotime($date)).'">today</span> at '. date('H:i', strtotime($date));
        }
        if(date('Ymd', strtotime('yesterday')) == date('Ymd', strtotime($date)))
        {
            return '<span title="'.date('j-n-Y', strtotime($date)).'">yesterday</span> at '. date('H:i', strtotime($date));
        }

        return 'at '. date('H:i / j-n-Y', strtotime($date));
    }






    // Install helper section

    // Create all tables necessary for PrettyForum to run
    // All built with Laravel's Schema builder
    public static function createInstallTables()
    {

        // Drop tables if they are already installed, drop them in this order to make sure no foreign key constraints errors will be triggered
        Schema::dropIfExists('forum_replies');
        Schema::dropIfExists('forum_topics');
        Schema::dropIfExists('forum_categories');
        Schema::dropIfExists('forum_sections');
        Schema::dropIfExists('users');

        // Create users table
        Schema::create('users', function($table)
        {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('ip_address');
            $table->string('avatar')->default('');
            $table->string('user_title')->default('');
            $table->text('signature')->default('');
            $table->timestamps();

        });

        // Create sections table (used to order forum, e.g. general chat/ gaming/ programming/
        Schema::create('forum_sections', function($table)
        {
            $table->increments('id');
            $table->string('title');

            $table->timestamps();
        });

        // Create categories, AKA forums
        Schema::create('forum_categories', function($table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->integer('parent_section')->unsigned();
            $table->foreign('parent_section')->references('id')->on('forum_sections')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('parent_category')->default(0);

            $table->timestamps();
        });


        // TODO: Add edited_by on forum_replies / forum_topics
        // Create table for topics
        Schema::create('forum_topics', function($table)
        {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('forum_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('closed')->default(0);
            $table->boolean('deleted')->default(0);

            $table->timestamps();

        });

        // Create table for replies
        Schema::create('forum_replies', function($table)
        {
            $table->increments('id');
            $table->text('body');
            $table->integer('topic_id')->unsigned();
            $table->foreign('topic_id')->references('id')->on('forum_topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('category_id')->on('forum_topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('deleted')->default(0);

            $table->timestamps();
        });
    }

    // Fill the created tables with the input from the installer page
    public static function fillInstallTables($input)
    {
        $section = new Section;
        $section->title = 'General';
        $section->save();

        $category = new Category;
        $category->title = 'Lounge';
        $category->description = 'Just talk about whatever is on your mind...';
        $category->parent_section = $section->id;
        $category->save();

        $user = new User;
        $user->username = $input['adminUsername'];
        $user->password = Hash::make($input['adminPassword']);
        $user->email = $input['adminEmail'];
        $user->ip_address = $_SERVER['REMOTE_ADDR'];
        $user->avatar = '/assets/img/avatar/standard.png';
        $user->user_title = 'I be like what..';
        $user->signature = 'Baas boven baas';
        $user->save();

        $topic = new Topic;
        $topic->title = 'Welcome!';
        $topic->body = 'Welcome to [b]your[/b] forum!';
        $topic->user_id = $user->id;
        $topic->category_id = $category->id;
        $topic->save();

        $reply = new Reply;
        $reply->body = 'This is a test reply';
        $reply->topic_id = $topic->id;
        $reply->user_id = $user->id;
        $reply->category_id = $category->id;
        $reply->save();

    }
}



