@extends('layouts.email')

@section('to-line', 'Hi '. $user->fullName('FLC') . ',')


@section('message-content')
    CZQO Staff have changed your status on the controller roster to:<br/>
    @switch ($controller->status)
        @case ('certified')
            <b>Certification:</b>&nbsp;Certified
        @break
        @case ('not_certified')
            <b>Certification:</b>&nbsp;Not Certified
        @break
        @case ('training')
            <b>Certification:</b>&nbsp;Training
        @break
        @case ('instructor')
            <b>Certification:</b>&nbsp;CZQO Instructor
        @break
    @endswitch
    <br/>
    @if ($controller->active == 1)
        <b>Activity:</b>&nbsp;Active
    @else
        <b>Activity:</b>&nbsp;Inactive
    @endif
    <br/>
    <p>
        If you believe there is an error, or have any questions, please do not hesitate to open up a support ticket.
    </p>
@stop

@section('footer-to-line', $user->fullName('FLC').' ('.$user->email.')')

@section('footer-reason-line')
as your status with Gander Oceanic has been changed
@endsection
