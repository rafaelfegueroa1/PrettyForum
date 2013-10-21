@extends('layouts.layout')



@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        {{ Form::open(array('action' => 'UserController@postRegister')) }}

        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', Input::old('username'), array('class' => 'form-control')) }}

        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', array('class' => 'form-control')) }}

        {{ Form::label('passwordConfirm', 'Confirm password') }}
        {{ Form::password('passwordConfirm', array('class' => 'form-control')) }}

        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', Input::old('email'),array('class' => 'form-control')) }}

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