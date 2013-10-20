<?php
/**
 * User: Rogier
 * Date: 17-10-13
 * Time: 22:28
 *
 */

class Category extends Eloquent {

    protected $table = 'forum_categories';

    // Get the section this category belongs to
    public function section()
    {
        return $this->belongsTo('Section', 'parent_section');
    }

    // Get all topics from this category
    public function topics()
    {
        return $this->hasMany('Topic', 'category_id');
    }

    public function replies()
    {
        return $this->hasMany('Reply', 'category_id');
    }

    public function hasReplies()
    {
        return $this->replies->count();
    }

}