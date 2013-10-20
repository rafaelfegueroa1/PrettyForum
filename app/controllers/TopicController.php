<?php
/**
 * User: Rogier
 * Date: 18-10-13
 * Time: 23:02
 *
 */

class TopicController extends BaseController {


    public function getShow($id = NULL)
    {
        $topic = Topic::find($id);


        // If there's no topic ID or ID is invalid return an error view
        if($topic == NULL) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found');


        $replies = $topic->replies()->paginate(20);

        return View::make('layouts.forum.topic.showTopic')
            ->with('topic', $topic)
            ->with('replies', $replies);

    }

}