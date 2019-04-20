@extends('layouts.email')


@section('title')
FIR Announcement
@stop

@section('to')

Dear {{$data['receivingname']}},
@stop

@section('content')
    {!!html_entity_decode($data['content'])!!}
@stop

@section('end')
From {{$data['fname']}} {{$data['lname']}}
@stop