<?php
/**
 * User: Rogier
 * Date: 17-10-13
 * Time: 23:08
 *
 */

class Reply extends Eloquent {

    protected $table = 'forum_replies';

    // Get topic this reply belongs to
    public function getTopic()
    {
        return $this->belongsTo('Topic', 'topic_id');
    }

    public function getCategory()
    {
        return $this->belongsTo('Category', 'category_id');
    }

    public function getUser()
    {
        return $this->belongsTo('User', 'user_id');
    }

}