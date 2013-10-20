<?php
/**
 * User: Rogier
 * Date: 17-10-13
 * Time: 23:05
 *
 */

class Topic extends Eloquent {

    protected $table = 'forum_topics';

    // Get category topics belongs to
    public function category()
    {
        return $this->belongsTo('Category', 'category_id');
    }

    // Get replies this topic has
    public function replies()
    {
        return $this->hasMany('Reply', 'topic_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }


}