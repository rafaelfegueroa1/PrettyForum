@extends('layouts.layout')



@section('content')

<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li class="active">Login</li>
</ul>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        {{ Form::open(array('action' => 'UserController@postLogin')) }}

        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', Input::old('username'), array('class' => 'form-control', 'max-length' => '25')) }}

        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', array('class' => 'form-control')) }}

        <div class="checkbox">
            {{ Form::label('remember', 'Remember me') }}
            {{ Form::checkbox('remember', '1') }}
        </div>

        <br/>
        @if(Session::has('loginError'))
        <p class="alert alert-danger">
            {{{ Session::get('loginError') }}}
        </p>
        @endif

        {{ Form::submit('Login', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>
</div>

@endsection