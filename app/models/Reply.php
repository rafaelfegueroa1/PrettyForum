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
    public function topic()
    {
        return $this->belongsTo('Topic', 'topic_id');
    }

    public function category()
    {
        return $this->belongsTo('Category', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }

}