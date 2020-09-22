@extends('layouts.master')

@section('navbarprim')
    @parent
@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Edit Biography</h2>
        <p>Please ensure this complies with the VATSIM Code of Conduct.</p>
        <form method="post" action="{{route('me.editbio')}}">
            @csrf
            <textarea id="contentMD" name="bio" class="w-75"></textarea>
            <script>
                var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
            </script>
            <br/>
            <input type="submit" class="btn btn-primary" value="Save">
        </form>
    </div>
@stop
