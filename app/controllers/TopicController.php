<?php
/**
 * User: Rogier
 * Date: 18-10-13
 * Time: 23:02
 *
 */

class TopicController extends BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth', array('except' => array('getShow')));

        $this->beforeFilter('csrf', array('except' => array('getShow', 'getReplyText', 'getEdit', 'getDelete', 'getNew')));
    }

    // Find and return view of topic
    public function getShow($id = NULL)
    {
        $topic = Topic::find($id);

        // If there's no topic ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found');

        // Where deleted to hide "deleted" replies
        $replies = $topic->replies()->paginate(20);

        return View::make('layouts.forum.topic.showTopic')
            ->with('topic', $topic)
            ->with('replies', $replies)
            ->with('bbcode', new Bbcode);
    }


    public function getNew($id = NULL)
    {
        $category = Category::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($category)) return View::make('layouts.error.standardError')
            ->with('error', 'Category not found.');


        return View::make('layouts.forum.topic.newTopic')
            ->with('category', $category);
    }

    public function postNew($id = NULL)
    {
        if(Auth::user()->topics()->count() > 0)
        {
            $timeDifference = (strtotime(Auth::user()->topics()->orderBy('id', 'DESC')->first()->created_at) + 30) - time();
            if($timeDifference > 0)
            {
                Session::flash('postTopicError', 'Please wait '.$timeDifference.' seconds before posting.');
                Input::flash();
                return Redirect::action('TopicController@getNew', $id);
            }
        }

        $category = Category::find($id);
        if(is_null($category))
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'Category not found');
        }
        $data = Input::get();

        // Make a validator to check if the topic can be posted
        $validator = Validator::make(
            $data,
            array(
                'topicTitle' => 'required|min:5',
                'topicBody' => 'required|min:15'
            )
        );
        // Validator fails? Return with error and the body of the topic
        if($validator->fails())
        {
            Session::flash('postTopicError', 'Your topic has to be at least 15 characters long.');
            Input::flash();
            return Redirect::action('TopicController@getNew', $id);
        }

        // Create new topic object and save it with the just posted contents
        $topic = new Topic;
        $topic->title = $data['topicTitle'];
        $topic->body = $data['topicBody'];
        $topic->user_id = Auth::user()->id;
        $topic->category_id = $category->id;
        $topic->save();

        return Redirect::action('TopicController@getShow', $topic->id);
    }

    public function getEdit($id = NULL)
    {
        $topic = Topic::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found.');

        if($topic->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot edit this topic.');
        }

        return View::make('layouts.forum.topic.editTopic')
            ->with('topic', $topic);


    }

    public function postEdit($id = NULL)
    {
        $topic = Topic::find($id);
        // If there's no topic ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Reply not found.');

        if($topic->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot edit this topic.');
        }

        $data = Input::get();

        // Make a validator to check if the post can be edited
        $validator = Validator::make(
            $data,
            array(
                'topicTitle' => 'required|min:5',
                'topicBody' => 'required|min:15'
            )
        );
        // Validator fails? Return with error and the body of the topic
        if($validator->fails())
        {
            Session::flash('editTopicError', 'Your topic body has to be at least 15 characters long.');
            Input::flash();
            return Redirect::action('TopicController@getEdit', $id);
        };


        $topic->title = $data['topicTitle'];
        $topic->body = $data['topicBody'];
        $topic->save();

        return Redirect::action('TopicController@getShow', $topic->id);
    }

    public function getDelete($id = NULL)
    {
        $topic = Topic::find($id);
        // If there's no topic ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found.');

        if($topic->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot delete this topic.');
        }

        return View::make('layouts.forum.topic.deleteTopic')
            ->with('topic', $topic)
            ->with('bbcode', new Bbcode);
    }

    public function postDelete($id = NULL)
    {
        $topic = Topic::find($id);
        // If there's no topic ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found.');

        if($topic->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot delete this topic.');
        }

        $topic->delete();
        return Redirect::action('ForumController@getShow', $topic->category_id);
    }

}