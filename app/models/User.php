<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');


    public function topics()
    {
        return $this->hasMany('Topic', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany('Reply', 'user_id');
    }


    // Return user's postcount
    public function getPostCount()
    {
        return $this->topics()->count() + $this->replies()->count();
    }

    // Check if user can modify this post
    public function canModifyPost($post)
    {
        // ToDo: Write function to check if user can modify post
        // Check for certain groups user belongs to, is admin, is mod etc
    }


	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


}