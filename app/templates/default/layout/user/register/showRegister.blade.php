@extends('layouts.layout')



@section('content')
<ul class="breadcrumb">
    <li><a class="forum-link" href="/">{{{ Config::get('settings.appSettings.forumName') }}}</a></li>
    <li class="active">Register</li>
</ul>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        {{ Form::open(array('action' => 'UserController@postRegister')) }}

        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', Input::old('username'), array('class' => 'form-control', 'max-length' => '25')) }}

        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', array('class' => 'form-control')) }}

        {{ Form::label('passwordConfirm', 'Confirm password') }}
        {{ Form::password('passwordConfirm', array('class' => 'form-control')) }}

        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', Input::old('email'),array('class' => 'form-control', 'max-length' => '60')) }}

        <div class="checkbox">
            {{ Form::label('remember', 'Remember me') }}
            {{ Form::checkbox('remember', '1', Input::old('remember')) }}
        </div>

        <br/>

        @if(Session::has('registerError'))
        <div class="alert alert-danger">
        <ul>
        @foreach($errors->all() as $error)
            <li>{{{ $error }}}</li>
        @endforeach
        </ul>
        </div>
        @endif

        {{ Form::submit('Register', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>
</div>

@endsection