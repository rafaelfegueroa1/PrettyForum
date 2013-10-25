@extends('layouts.layout')

@section('title')
New topic - {{{ Config::get('settings.appSettings.defaultTitle') }}}
@endsection


@section('content')
<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li><a class="forum-link" href="/#{{{ $category->parent_section }}}">{{{ $category->section->title }}}</a></li>
    <li><a class="forum-link" href="{{{ action('ForumController@getShow', $category->id) }}}">{{{ $category->title }}}</a></li>
    <li class="active">New topic</li>
</ul>


<div>

    {{ Form::open(array('action' => array('TopicController@postNew', $category->id))) }}

    {{ Form::label('topicTitle', 'Topic title') }}
    {{ Form::text('topicTitle', Input::old('topicTitle'), array('class' => 'form-control', 'max-length' => '60')) }}

    <br/>

    {{-- The textarea gets its markup and actions from jquery.bbcode.js, located in the assets/js/ folder --}}

    {{ Form::label('topicBody', 'Topic body') }}
    {{ Form::textarea('topicBody', Input::old('topicBody'), array('class' => 'form-control', 'id' => 'topicBody')) }}
    <script>$('#topicBody').bbcode();</script>
    <br/>
    @if(Session::has('postTopicError'))

    <p class="alert alert-danger">
        {{{ Session::get('postTopicError') }}}
    </p>
    @endif

    {{ Form::submit('Post topic', array('id' => 'postBtn', 'class' => 'btn btn-primary')) }}
    {{ Form::close() }}
</div>

@endsection