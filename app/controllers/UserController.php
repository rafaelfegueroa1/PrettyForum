<?php
/**
 * UserController
 * Used to route all user related stuff e.g.
 * - Login
 * - Register
 *
 */

class UserController extends BaseController {

    // Instantiate the UserController and add the necessary filters
    public function __construct()
    {
        $this->beforeFilter('auth', array('except' => array('index', 'login', 'register')));
    }


    public function getIndex()
    {
        if(Auth::check())
        {
            return Redirect::to(URL::to('/'));
        }
        return Redirect::action('UserController@getLogin');
    }

    public function getLogin()
    {

    }

    public function postLogin()
    {

    }

    public function getRegister()
    {

    }

    public function postRegister()
    {

    }
}