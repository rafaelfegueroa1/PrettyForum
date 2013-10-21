@extends('install.setuplayout')


@section('content')

{{ Form::open(array('id' => 'installForm')) }}

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
{{ Form::password('databasePassword', '', array('class'=>'form-control')) }}

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

{{ Form::label('forumTimezone', 'Forum timezone') }}
{{ Form::text('forumTimezone', 'Europe/Amsterdam', array('class'=>'form-control')) }}
<br/>
<p class="alert alert-info">
    Don't know your timezone? Find it at <a href="http://php.net/manual/en/timezones.php" target="_blank">PHP.net</a>
</p>
<br/>
<legend>Admin account</legend>
{{ Form::label('adminUsername', 'Admin username:') }}
{{ Form::text('adminUsername', '', array('class'=>'form-control')) }}

{{ Form::label('adminPassword', 'Admin password:') }}
{{ Form::password('adminPassword', array('class'=>'form-control')) }}

<p class="alert alert-danger" id="passwordError" style="margin-top:10px;">
   Passwords do not match.
</p>
{{ Form::label('adminPasswordConfirm', 'Confirm password:') }}
{{ Form::password('adminPasswordConfirm', array('class'=>'form-control')) }}

{{ Form::label('adminEmail', 'Admin email:') }}
{{ Form::email('adminEmail', '', array('class'=>'form-control')) }}


<br/>
<legend>Security</legend>
{{ Form::label('uninstallKey', 'Uninstall key:') }}
{{ Form::text('uninstallKey', '', array('class'=>'form-control')) }}
<br/>

<p class="alert alert-info">
    Please save your uninstall key somewhere safe!
</p>
{{ Form::button('Install PrettyForum', array('class'=> 'btn btn-primary', 'id' => 'installBtn')) }}

<script>
    $(document).ready( function()
    {
       $('#passwordError').hide();
       $('#installBtn').click( function()
       {
           $('#passwordError').hide();
           if($('#adminPassword').val() != $('#adminPasswordConfirm').val())
           {
               $('#passwordError').show();
               return;
           }
           $('#installForm').submit();
       });
    });
</script>



{{ Form::close() }}

@endsection