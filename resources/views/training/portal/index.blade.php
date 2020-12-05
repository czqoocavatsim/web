@extends('training.portal.layouts.main')
@section('title', 'Training Portal - ')

@section('portal-content')
<div class="container py-4">
    <h2 class="blue-text mb-4"><span id="greeting">Hello</span>, {{Auth::user()->fullName('F')}}!</h2>
    @can('start applications')
    <div class="list-group-item rounded p-4 my-3 z-depth-1 shadow-none">
        <h4 class="blue-text"><i style="margin-right: 10px;" >👋</i>Apply for Gander Oceanic Certification</h4>
        <p style="font-size: 1.1em;">Interested in joining our team of oceanic controllers?</p>
        <p style="font-size: 1.2em;" class="mt-3 mb-0">
            <a class="font-weight-bold text-body" href="{{route('training.applications.apply')}}">Start your application &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
        </p>
    </div>
    @endcan
    @if(Auth::user()->studentProfile && Auth::user()->studentProfile->current)
    @php ($studentProfile = Auth::user()->studentProfile)
    <div class="list-group-item rounded p-4 my-3 z-depth-1 shadow-none">
        <h4 class="blue-text"><i class="fas fa-graduation-cap mr-2"></i>Your progress</h4>
        <h4 class="my-3">
        @foreach($studentProfile->labels as $label)
            <span class="mr-2">
                {{$label->label()->labelHtml()}}
            </span>
        @endforeach
        </h4>
        <div class="progress">
            <div class="progress-bar blue" id="studentProgressBar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex flex-row pt-2">
            <div style="width:20%;" class="text-center">
                Waiting for pick-up
            </div>
            <div style="width:20%;"  class="text-center">
                On the way
            </div>
            <div style="width:20%;"  class="text-center">
                Solo certification
            </div>
            <div style="width:20%;"  class="text-center">
                Ready for OTS
            </div>
            <div style="width:20%;"  class="text-center">
                Completed!
            </div>
        </div>
        @if ($studentProfile->hasLabel("Not Ready"))
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
    @endif
    <div class="list-group-item rounded p-4 my-3 z-depth-1 shadow-none">
        <h4 class="blue-text"><i class="fas fa-paper-plane mr-2"></i>Check out our resources on oceanic flight</h4>
        <p style="font-size: 1.1em;">We've created a large range of tutorials and resources on what it takes to fly and control over the North Atlanic Ocean. Check it for yourself!</p>
        <p style="font-size: 1.2em;" class="mt-3 mb-0">
            <a class="font-weight-bold text-body" href="https://knowledgebase.ganderoceanic.com">CZQO Knowledge Base &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        var date = new Date();
        if (date.getHours() < 12) { $("#greeting").text("Good morning") }
        if (date.getHours() < 17) { $("#greeting").text("Good afternoon") }
        if (date.getHours() > 17) { $("#greeting").text("Good evening") }
    })
</script>
@endsection
