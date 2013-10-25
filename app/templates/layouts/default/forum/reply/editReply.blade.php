@extends('layouts.layout')

@section('title')
Edit reply - {{{ Config::get('settings.appSettings.defaultTitle') }}}
@endsection

@section('content')
<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li><a class="forum-link" href="/#{{{ $topic->category->parent_section }}}">{{{ $topic->category->section->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('ForumController@getShow', $topic->category->id) }}}">{{{ $topic->category->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('TopicController@getShow', $topic->id) }}}">{{{ Helper::shorten($topic->title, 60) }}}</a></li>
    <li class="active">Edit reply</li>


</ul>

<div>

    {{ Form::open(array('action' => array('ReplyController@postEdit', $reply->id))) }}



    {{-- The textarea gets its markup and actions from jquery.bbcode.js, located in the assets/js/ folder --}}

    {{ Form::textarea('replyBody', (Session::has('postReplyBody')) ? Session::get('postReplyBody') : $reply->body, array('class' => 'form-control', 'id' => 'replyArea')) }}
    <script>$('#replyArea').bbcode();</script>
    <br/>
    @if(Session::has('postReplyError'))

    <p class="alert alert-danger">
        {{{ Session::get('postReplyError') }}}
    </p>
    @endif

    {{ Form::submit('Post edit', array('id' => 'postBtn', 'class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>



@endsection