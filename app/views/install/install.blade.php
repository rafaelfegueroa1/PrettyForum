@extends('install.setuplayout')


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
<p class="alert alert-info">
    The database user has to be able to create databases, tables and drop them.
</p>

<br/>
<legend>E-mail</legend>
{{ Form::label('emailFrom', 'From:') }}
{{ Form::text('emailFrom', '', array('class'=>'form-control')) }}

{{ Form::label('emailName', 'Name to display:') }}
{{ Form::text('emailName', '', array('class'=>'form-control')) }}

<br/>
<legend>Application settings</legend>
{{ Form::label('defaultTitle', 'Default page title') }}
{{ Form::text('defaultTitle', '', array('class'=>'form-control')) }}

{{ Form::label('forumName', 'Forum name:') }}
{{ Form::text('forumName', '', array('class'=>'form-control')) }}


<br/>
<legend>Security</legend>
{{ Form::label('uninstallKey', 'Uninstall key:') }}
{{ Form::text('uninstallKey', '', array('class'=>'form-control')) }}
<br/>

<p class="alert alert-info">
    Please save your uninstall key somewhere safe!
</p>
{{ Form::submit('Install PrettyForum', array('class'=> 'btn btn-primary')) }}



{{ Form::close() }}

@endsection