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
    const DEFAULTFOLDER = '../app/views/default/';
    const VIEWSFOLDER = '../app/views/';

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

    // Helper to remove files in a dir, works recursively
    public static function removeFilesFromFolder($dir) {
        $files = glob( $dir . '*', GLOB_MARK );

        foreach( $files as $file ){
            if( substr( $file, -1 ) == '/' )
                self::removeFilesFromFolder( $file );
            else
                unlink( $file );
        }

    }







    // Install helper section


    // Create all tables necessary for PrettyForum to run
    // All built with Laravel's Schema builder
    public static function createInstallTables()
    {
        // Create users table
        Schema::dropIfExists('users');
        Schema::create('users', function($table)
        {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();

            $table->timestamps();

        });

        // Create sections table (used to order forum, e.g. general chat/ gaming/ programming/
        Schema::dropIfExists('forum_sections');
        Schema::create('forum_sections', function($table)
        {
            $table->increments('id');
            $table->string('title');

            $table->timestamps();
        });

        // Create categories, AKA forums
        Schema::dropIfExists('forum_categories');
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

        // Create table for topics
        Schema::dropIfExists('forum_topics');
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
        Schema::dropIfExists('forum_replies');
        Schema::create('forum_replies', function($table)
        {
            $table->increments('id');
            $table->text('body');
            $table->integer('topic_id')->unsigned();
            $table->foreign('topic_id')->references('id')->on('forum_topics')
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->boolean('deleted')->default(0);

            $table->timestamps();
        });
    }
}



