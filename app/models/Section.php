<?php
/**
 * User: Rogier
 * Date: 17-10-13
 * Time: 22:26
 *
 */

class Section extends Eloquent {

    protected $table = 'forum_sections';

    // Get all categories that belong to this section
    public function getCategories()
    {
        return $this->hasMany('Category', 'parent_section');
    }
}