<?php
/**
 * User: Rogier
 * Date: 18-10-13
 * Time: 20:52
 *
 */

class ForumController extends BaseController {




    // Show action of the Forum controller
    public function getShow($id = NULL)
    {
        $category = Category::with('topics.replies')->find($id);


        // If there's no category ID or ID is invalid return an error view
        if($category == NULL) return View::make('layouts.error.standardError')
                                       ->with('error', 'Category not found');


        $topics = $category->topics()->paginate(20);

        return View::make('layouts.forum.category.showCategory')
            ->with('category', $category)
            ->with('topics', $topics);

    }

}