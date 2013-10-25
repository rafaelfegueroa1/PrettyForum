<?php
/**
 * User: Rogier
 * Date: 21-10-13
 * Time: 21:20
 *
 */

class ReplyController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter('csrf', array('only' => array('postEdit', 'postDelete', 'postNew')));
    }

    // Post a reply to a topic, defined by ID
    public function postNew($id = NULL)
    {
        // Check if 30 seconds have passed since user posted
        if(Auth::user()->replies()->count() > 0)
        {
            $timeDifference = (strtotime(Auth::user()->replies()->orderBy('id', 'DESC')->first()->created_at) + 30) - time();
            if($timeDifference > 0)
            {
                Session::flash('postReplyError', 'Please wait '.$timeDifference.' seconds before posting.');
                Session::flash('postReplyBody', Input::get('replyBody'));
                return Redirect::action('TopicController@getShow', array($id));
            }
        }

        $topic = Topic::find($id);
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

        // If last reply in this topic is from this user, edit that reply and append the text he just posted
        if($topic->replies()->count() > 0)
        {
            $lastReply = $topic->replies()->orderBy('id', 'DESC')->first();
            if($lastReply->user_id == Auth::user()->id)
            {
                $reply = $lastReply;
                $reply->body .= "\n\r \n\r [u][size=80]Added on ".date('H:i / j-n-Y', time())."[/size][/u]\n\r".$data['replyBody'];
                $reply->save();


                return Redirect::to(action('TopicController@getShow', $topic->id).'#'.$reply->id);
            }
        }

        $reply = new Reply;
        $reply->body = $data['replyBody'];
        $reply->topic_id = $topic->id;
        $reply->user_id = Auth::user()->id;
        $reply->category_id = $topic->category_id;
        $reply->save();

        return Redirect::to(action('TopicController@getShow', $topic->id).'#'.$reply->id);
    }

    // Action for editing a reply
    public function getEdit($id = NULL)
    {
        $reply = Reply::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($reply)) return View::make('layouts.error.standardError')
           ->with('error', 'Reply not found.');

        if($reply->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot edit this post.');
        }

        return View::make('layouts.forum.reply.editReply')
            ->with('reply', $reply)
            ->with('topic', $reply->topic()->first());

    }

    // Action for when edited reply has been sent through POST
    public function postEdit($id = NULL)
    {

        $reply = Reply::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($reply)) return View::make('layouts.error.standardError')
            ->with('error', 'Reply not found.');

        if($reply->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot edit this post.');
        }

        $data = Input::get();

        // Make a validator to check if the post can be edited
        $validator = Validator::make(
            $data,
            array('replyBody' => 'required|min:15')
        );
        // Validator fails? Return with error and the body of the reply
        if($validator->fails())
        {
            Session::flash('postReplyError', 'Your reply has to be at least 15 characters long.');
            Session::flash('postReplyBody', $data['replyBody']);
            return Redirect::action('ReplyController@getEdit', array($id));
        };


        $reply->body = $data['replyBody'];
        $reply->save();

        return Redirect::to(action('TopicController@getShow', $reply->topic_id).'#'.$reply->id);

    }


    public function getDelete($id = NULL)
    {
        $reply = Reply::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($reply)) return View::make('layouts.error.standardError')
            ->with('error', 'Reply not found.');

        if($reply->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot delete this post.');
        }

        return View::make('layouts.forum.reply.deleteReply')
            ->with('reply', $reply)
            ->with('topic', $reply->topic()->first())
            ->with('bbcode', new BbCode);
    }

    public function postDelete($id = NULL)
    {
        $reply = Reply::find($id);
        // If there's no reply ID or ID is invalid return an error view
        if(is_null($reply)) return View::make('layouts.error.standardError')
            ->with('error', 'Reply not found.');

        if($reply->user_id != Auth::user()->id && !Auth::user()->canModifyPost())
        {
            return View::make('layouts.error.standardError')
                ->with('error', 'You cannot delete this post.');
        }

        $reply->delete();
        return Redirect::action('TopicController@getShow', $reply->topic_id);
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
}