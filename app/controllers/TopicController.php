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

        $this->beforeFilter('csrf', array('except' => array('getShow', 'getReplyText')));
    }

    // Find and return view of topic
    public function getShow($id = NULL)
    {
        $topic = Topic::find($id);


        // If there's no topic ID or ID is invalid return an error view
        if(is_null($topic)) return View::make('layouts.error.standardError')
            ->with('error', 'Topic not found');

        // Where deleted to hide "deleted" replies
        $replies = $topic->replies()->where('deleted', '=', '0')->paginate(20);

        return View::make('layouts.forum.topic.showTopic')
            ->with('topic', $topic)
            ->with('replies', $replies);
    }


    // getReplyText responds to an AJAX call that is triggered when a user hits quote on a post
    // Returns the replies text
    public function getReplyText($id = NULL)
    {
        if(!Request::ajax())
        {
            return 'Request not AJAX';
        }
        $reply = Reply::find($id);
        if(is_null($reply))
        {
            return 'No reply with that ID';
        }
        return json_encode(array(
            'id' => $reply->id,
            'body' => $reply->body,
            'username' => $reply->user->username
        ));
    }


    // Post a reply to a topic, defined by ID
    public function postReply($id = NULL)
    {
        $topic = Topic::find($id);

        // TODO: Make double post edit last post

        if(is_null($topic))
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'Topic not found');
        }
        $data = Input::get();

        // Make a validator to check if the post can be posted
        $validator = Validator::make(
            $data,
            array('replyBody' => 'required|min:15')
        );
        // Validator fails? Return with error and the body of the reply
        if($validator->fails())
        {
            Session::flash('postReplyError', 'Your reply has to be at least 15 characters long.');
            Session::flash('postReplyBody', $data['replyBody']);
            return Redirect::action('TopicController@getShow', array($id));
        }

        $reply = new Reply;
        $reply->body = $data['replyBody'];
        $reply->topic_id = $topic->id;
        $reply->user_id = Auth::user()->id;
        $reply->category_id = $topic->category_id;
        $reply->save();

        return Redirect::to('/topic/show/'.$topic->id.'#'.$reply->id);

    }

}