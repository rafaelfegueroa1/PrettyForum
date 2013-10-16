@extends('install.setuplayout')


@section('content')



@if($installed == 1)
{{ Form::open() }}
<br/>
<h2>PrettyForum uninstall</h2>
<br/>
<p>
    What uninstalling PrettyForum will do:
    <ul>
        <li>Remove all settings</li>
        <li>Remove installed layouts</li>
    </ul>
    What uninstalling won't do:
    <ul>
        <li>Remove PrettyForum itself</li>
        <li>Touch the database in any way</li>
    </ul>
    <br/>
    This process is irreversible, are you sure?

</p>
<br/>
<br/>

@if(Session::has('wrongUninstallKey'))
<p class="alert alert-danger">
    The entered uninstall key is not valid.
</p>
@endif

{{ Form::label('uninstallKey', 'Uninstall key:') }}
{{ Form::text('uninstallKey', '', array('class' => 'form-control')) }}
<br/>
{{ Form::submit('Uninstall PrettyForum', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}



@else

<h3>Sorry...</h3>
<p>
    It seems like PrettyForum is not installed, so no need to uninstall.
    <br/>
    <a href="/setup">Click here</a> to install PrettyForum
</p>

@endif

@endsection