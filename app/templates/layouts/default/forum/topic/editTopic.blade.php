@extends('layouts.layout')

@section('title')
Edit topic - {{{ Config::get('settings.appSettings.defaultTitle') }}}
@endsection



@section('content')
<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li><a class="forum-link" href="/#{{{ $topic->category->parent_section }}}">{{{ $topic->category->section->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('ForumController@getShow', $topic->category->id) }}}">{{{ $topic->category->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('TopicController@getShow', $topic->id) }}}">{{{ Helper::shorten($topic->title, 60) }}}</a></li>
    <li class="active">Edit topic</li>


</ul>

<div>

    {{ Form::open(array('action' => array('TopicController@postEdit', $topic->id))) }}

    {{ Form::label('topicTitle', 'Topic title') }}
    {{ Form::text('topicTitle', (Session::has('editTopicError') ? Input::old() : $topic->title), array('class' => 'form-control', 'max-length' => '60')) }}

    <br/>

    {{-- The textarea gets its markup and actions from jquery.bbcode.js, located in the assets/js/ folder --}}

    {{ Form::label('topicBody', 'Topic body') }}
    {{ Form::textarea('topicBody', (Session::has('editTopicError') ? Input::old() : $topic->body), array('class' => 'form-control', 'id' => 'topicBody')) }}
    <script>$('#topicBody').bbcode();</script>
    <br/>
    @if(Session::has('editTopicError'))

    <p class="alert alert-danger">
        {{{ Session::get('editTopicError') }}}
    </p>
    @endif

    {{ Form::submit('Post edit', array('id' => 'postBtn', 'class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>



@endsection