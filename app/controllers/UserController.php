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
        $this->beforeFilter('guest', array('only' => array('getLogin', 'postLogin', 'getRegister', 'postRegister')));
        $this->beforeFilter('auth', array('only' => array('getLogout')));
        $this->beforeFilter('csrf', array('except' => array('getIndex', 'getLogin', 'getRegister')));
    }


    // If someone visited /user/ redirect them to / or login page
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
        return View::make('layouts.user.login.showLogin');
    }

    // If someone is trying to login
    public function postLogin()
    {
        $data = Input::get();
        if( Auth::attempt(
            array(
                'username' => $data['username'],
                'password' => $data['password']
            ),
            (isset($data['remember'])) ? true : false
            ))
        {
            // Log the IP address that just logged onto this account
            $loginLogger = new Loginlogger;
            $loginLogger->user_id = Auth::user()->id;
            $loginLogger->ip_address = Request::server('REMOTE_ADDR');
            $loginLogger->save();

            return Redirect::to('/');
        }




        Input::flashOnly('username');
        Session::flash('loginError', 'Username or password is incorrect.');
        return Redirect::action('UserController@getLogin');
    }

    public function getRegister()
    {
        return View::make('layouts.user.register.showRegister');
    }

    public function postRegister()
    {
        $data = Input::get();
        $rules = array(
            'username' => 'required|min:3|max:25|unique:users|alpha_dash|',
            'password' => 'required|min:5',
            'passwordConfirm' => 'required|same:password',
            'email' => 'required|email|max:60|unique:users'
        );
        $messages = array(
            'passwordConfirm.same' => 'Passwords do no match.',
            'passwordConfirm.required' => 'The confirm password field is required.'
        );
        $validator = Validator::make($data, $rules, $messages);
        if($validator->fails())
        {
            Session::flash('registerError', true);
            Input::flashOnly('username', 'password');
            return Redirect::action('UserController@getRegister')->withErrors($validator);
        }

        $user = new User;
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->ip_address = Request::server('REMOTE_ADDR');
        $user->save();

        Auth::attempt(
            array(
                'username' => $data['username'],
                'password' => $data['password']
            ),
            (isset($data['remember'])) ? true : false
        );

        return Redirect::to('/');
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/');
    }



    public function getProfile($id = NULL)
    {

    }

}