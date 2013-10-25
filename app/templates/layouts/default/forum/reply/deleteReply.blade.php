@extends('layouts.layout')

@section('title')
Delete reply - {{{ Config::get('settings.appSettings.defaultTitle') }}}
@endsection

@section('script')
<script>
    $(document).ready(function()
    {
        $('#noBtn').click(function()
        {
            location.href = 'javascript:history.go(-1)';
        });
    });
</script>

@endsection

@section('content')
<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li><a class="forum-link" href="/#{{{ $topic->category->parent_section }}}">{{{ $topic->category->section->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('ForumController@getShow', $topic->category->id) }}}">{{{ $topic->category->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('TopicController@getShow', $topic->id) }}}">{{{ Helper::shorten($topic->title, 60) }}}</a></li>
    <li class="active">Delete reply</li>


</ul>

{{ Form::open(array('action' => array('ReplyController@postDelete', $reply->id))) }}

<p class="alert alert-warning">
    Are you sure you want to delete this post?
</p>
<div class="row">
    <div class="col-md-2 col-md-offset-5">
        {{ Form::submit('Yes', array('class' => 'btn btn-success col-md-offset-3')) }}
        {{ Form::button('No', array('class' => 'btn btn-danger', 'id' => 'noBtn')) }}
    </div>
</div>

{{ Form::close() }}


<div class="post-container">
    <div class="row post-head">
        <div class="col-md-12">
            <div class="post-title">
                Reply by {{{ $reply->user->username }}}
            </div>

            <div class="small-text post-date-info">
                posted {{ Helper::getDateFormatted($reply->created_at) }}

                @if($reply->created_at != $reply->updated_at)
                        <span class="small-text">
                            modified {{ Helper::getDateFormatted($reply->updated_at) }}
                        </span>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <div class="user-info row">
        <div class="col-md-6">
            <div class="pull-left">
                <img src="{{{ $reply->user->avatar}}}" class="avatar" />
            </div>


            <h5><a class="forum-link" href="{{{ action('UserController@getProfile', $reply->user->id) }}}">{{{ $reply->user->username }}}</a></h5>
            <span>{{{ $reply->user->user_title }}}</span>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6">
            <div class="user-info-posts pull-right">
                <table>
                    <tbody>
                    <tr>
                        <td>Posts: </td>
                        <td>{{{ $reply->user->getPostCount() }}}</td>
                    </tr>
                    <tr>
                        <td>Joined:</td>
                        <td>{{{ date('M Y', strtotime($reply->user->created_at)) }}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="post-body">
        {{ $bbcode->toHTML($reply->body) }}
        <br/>
    </div>
    @if(!empty($reply->user->signature))
    <div class="post-signature">
        {{ $bbcode->toHTML($reply->user->signature) }}
    </div>
    @endif
</div>

@endsection