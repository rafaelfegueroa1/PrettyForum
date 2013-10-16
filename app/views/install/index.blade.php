@extends('install.installlayout')


@section('content')

{{ Form::open() }}

<h2>PrettyForum setup</h2>
Server time: {{ date('d-m-Y / H:i') }}
<br/>
<br/>
<legend>Database</legend>
{{ Form::label('databaseHost', 'Database host:') }}
{{ Form::text('databaseHost', '', array('class'=>'form-control')) }}

{{ Form::label('databaseUsername', 'Database username:') }}
{{ Form::text('databaseUsername', '', array('class'=>'form-control')) }}

{{ Form::label('databasePassword', 'Database password:') }}
{{ Form::text('databasePassword', '', array('class'=>'form-control')) }}

<br/>
<legend>E-mail</legend>
{{ Form::label('emailFrom', 'From:') }}
{{ Form::text('emailFrom', '', array('class'=>'form-control')) }}

{{ Form::label('emailName', 'Name to display:') }}
{{ Form::text('emailName', '', array('class'=>'form-control')) }}

<br/>
{{ Form::submit('Install PrettyForum', array('class'=> 'btn btn-primary')) }}



{{ Form::close() }}

@endsection