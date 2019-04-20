@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Meeting Minutes</h2>
        <br/>
        @if (count($minutes) >= 1)
        <table class="table">
            <tbody>
                @foreach ($minutes as $m)
                <tr>
                    <td>{{$m->title}}</td>
                    @if(Auth::check() && Auth::user()->permissions >= 3)
                    <td>Added by {{\App\User::find($m->user_id)->fullName('FLC')}}</td>
                    @endif
                    <td>
                    <a target="_blank" href="{{$m->link}}">View</a>
                    </td>
                    @if(Auth::check() && Auth::user()->permissions >= 3)
                    <td>
                    <a href="{{route('meetingminutes.delete', $m->id)}}" style="color: red;"><i class="fa fa-times"></i>&nbsp;Delete</a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        no meeting minutes.. bruh
        @endif
        <br/>
        @if (Auth::check() && Auth::user()->permissions >= 3)
        <a href="#" data-toggle="modal" data-target="#upload">Upload Minutes</a>
        <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Upload</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="{{route('meetingminutes.upload')}}" enctype="multipart/form-data">
                                @csrf
                                <label>Title</label>
                                <input type="text" name="title" class="form-control">
                                <br/>
                                <input type="file" name="file" class="form-control-file">
                                <br/>
                                <input type="submit" class="btn btn-success" value="Upload">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop
