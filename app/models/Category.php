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
    public function getSection()
    {
        return $this->belongsTo('Section', 'parent_section');
    }

    // Get all topics from this category
    public function getTopics()
    {
        return $this->hasMany('Topic', 'category_id');
    }

    public function getReplies()
    {
        return $this->hasMany('Reply', 'category_id');
    }

}