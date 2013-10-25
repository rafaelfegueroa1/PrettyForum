<?php
/**
 * User: Rogier
 * Date: 24-10-13
 * Time: 20:53
 *
 */

class Loginlogger extends Eloquent {

    protected $table = 'user_logins';

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

}