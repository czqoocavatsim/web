@extends('training.portal.layouts.main')
@section('page-header-title', 'Your progress')
@section('portal-content')
<div class="container py-4">
    <h4 class="blue-text">Status label</h4>
    <h3 class="mb-4">
    @foreach($studentProfile->labels as $label)
        <span class="mr-2" data-toggle="tooltip" title="Since {{$label->created_at->diffForHumans()}}">
            {{$label->label()->labelHtml()}}
        </span>
    @endforeach
    </h3>
    <h4 class="blue-text my-3">Stages</h4>
    <div class="progress">
        <div class="progress-bar blue" id="studentProgressBar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="d-flex flex-row pt-2">
        <div style="width:20%;" data-toggle="tooltip" title="You have submitted your availability and you are waiting for an Instructor to pick you up." class="text-center">
            Waiting for pick-up
        </div>
        <div style="width:20%;" data-toggle="tooltip" title="Your training is in progress." class="text-center">
            On the way
        </div>
        <div style="width:20%;" data-toggle="tooltip" title="You are currently on a Solo Certification." class="text-center">
            Solo certification
        </div>
        <div style="width:20%;" data-toggle="tooltip" title="Your Instructor has recommended you for assessment." class="text-center">
            Ready for OTS
        </div>
        <div style="width:20%;" data-toggle="tooltip" title="You have completed your training. Congratulations!" class="text-center">
            Completed!
        </div>
    </div>
    @if ($studentProfile->hasLabel("Awaiting Exam"))
        <script>
            $('#studentProgressBar').css('width', '0%')
        </script>
    @endif
    @if ($studentProfile->hasLabel("Ready For Pick-Up"))
        <script>
            $('#studentProgressBar').css('width', '20%')
        </script>
    @endif
    @if ($studentProfile->hasLabel("In Progress"))
        <script>
            $('#studentProgressBar').css('width', '40%')
        </script>
    @endif
    @if ($studentProfile->hasLabel("Solo Certification"))
        <script>
            $('#studentProgressBar').css('width', '60%')
        </script>
    @endif
    @if ($studentProfile->hasLabel("Ready For Assessment"))
        <script>
            $('#studentProgressBar').css('width', '80%')
        </script>
    @endif
    @if ($studentProfile->hasLabel("Completed"))
        <script>
            $('#studentProgressBar').css('width', '100%')
        </script>
    @endif
</div>
@endsection
