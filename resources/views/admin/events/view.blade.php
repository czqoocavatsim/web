@extends('layouts.primary', ['adminNavBar'=>true])
@section('content')
<div class="container py-4">
    <a href="{{route('events.admin.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Events</a>
    <h1 class="font-weight-bold blue-text">{{$event->name}}</h1>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <h4 class="font-weight-bold blue-text">Actions</h4>
            <ul class="list-unstyled mt-3 mb-0" style="font-size: 1.05em;">
                @can('edit event')
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#editEvent" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Edit event details</span></a>
                </li>
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#createUpdate" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Create update</span></a>
                </li>
                @endcan
                {{-- <li class="mb-2">
                    <a href="" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Export controller applications</span></a>
                </li> --}}
                @can('delete event')
                <li class="mb-2">
                    <a href="" data-toggle="modal" data-target="#deleteEvent" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="text-body">Delete event</span></a>
                </li>
                @endcan
            </ul>
        </div>
        <div class="col-md-9">
            <h4 class="font-weight-bold blue-text">Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-striped">
                        <tbody>
                            <tr>
                                <td>Start Time</td>
                                <td>{{$event->start_timestamp_pretty()}}</td>
                            </tr>
                            <tr>
                                <td>End Time</td>
                                <td>{{$event->end_timestamp_pretty()}}</td>
                            </tr>
                            <tr>
                                <td>Departure Airport</td>
                                <td>{{$event->departure_icao}}</td>
                            </tr>
                            <tr>
                                <td>Arrival Airport</td>
                                <td>{{$event->arrival_icao}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    @if ($event->image_url)
                    <img src="{{$event->image_url}}" alt="" class="img-fluid w-50 img-thumbnail">
                    @else
                    No image.
                    @endif
                </div>
            </div>
            <h4 class="font-weight-bold blue-text">Description</h4>
            {{$event->html()}}
            <h4 class="font-weight-bold blue-text">Updates</h4>
            @if (count($updates) == 0)
                None yet!
            @else
                @foreach($updates as $u)
                    <div class="card p-3">
                        <h4>{{$u->title}}</h4>
                        <div class="d-flex flex-row align-items-center">
                            <i class="far fa-clock"></i>&nbsp;&nbsp;Created {{$u->created_pretty()}}</span>&nbsp;&nbsp;•&nbsp;&nbsp;<i class="far fa-user-circle"></i>&nbsp;&nbsp;{{$u->author_pretty()}}&nbsp;&nbsp;•&nbsp;&nbsp;<a href="{{route('events.admin.update.delete', [$event->slug, $u->id])}}" class="red-text">Delete</a>
                        </div>
                        <hr>
                        {{$u->html()}}
                    </div>
                @endforeach
            @endif
            <h4 class="font-weight-bold blue-text mt-3">Controller Applications</h4>
            @if (count($applications) == 0)
                None yet!
            @else
                @foreach($applications as $a)
                    <div class="card p-3">
                        <h5>{{$a->user->fullName('FLC')}} ({{$a->user->rating_GRP}}, {{$a->user->division_name}})</h5>
                        <p>{{$a->start_availability_timestamp}} to {{$a->end_availability_timestamp}}</p>
                        <h6>Comments</h6>
                        <p>{{$a->comments}}</p>
                        <h6>Email</h6>
                        <p>{{$a->user->email}}</p>
                        <a href="{{route('events.admin.controllerapps.delete', [$event->slug, $a->user_id])}}" class="red-text">Delete</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@can('delete event')
<!--Delete event modal-->
<div class="modal fade" id="deleteEvent" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This will soft delete the event, so it still exists in the database but cannot be viewed. Have a funny GIF too.</p>
                <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="{{route('events.admin.delete', $event->slug)}}" role="button" class="btn btn-danger">Delete Event</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete event modal-->
@endcan

@can('edit event')
<!--Edit event modal-->
<div class="modal fade" id="editEvent" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit {{$event->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('events.admin.edit.post', $event->slug)}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($errors->editEventErrors->any())
                    <div class="alert alert-danger">
                        <h4>An error occurred whilst trying to edit the event</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editEventErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <ul class="stepper mt-0 p-0 stepper-vertical">
                        <li class="active">
                            <a href="#!">
                                <span class="circle">1</span>
                                <span class="label">Primary information</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                <div class="form-group">
                                    <label for="">Event name</label>
                                    <input type="text" name="name" id="" class="form-control" value="{{$event->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Start date and time</label>
                                    <input type="datetime" name="start" value="{{$event->start_timestamp}}" placeholder="Event start date/time" class="form-control flatpickr" id="event_start">
                                </div>
                                <div class="form-group">
                                    <label for="">End date and time</label>
                                    <input type="datetime" name="end" value="{{$event->end_timestamp}}" placeholder="Event end date/time" class="form-control flatpickr" id="event_end">
                                </div>
                                <div class="form-group">
                                    <label for="">Departure airport ICAO (optional)</label>
                                    <input maxlength="4" type="text" value="{{$event->departure_icao}}" name="departure_icao" id="" class="form-control" placeholder="CYYC">
                                </div>
                                <div class="form-group">
                                    <label for="">Arrival airport ICAO (optional)</label>
                                    <input maxlength="4" type="text" value="{{$event->arrival_icao}}" name="arrival_icao" id="" class="form-control" placeholder="EIDW">
                                </div>
                                <script>
                                    flatpickr('#event_start', {
                                        enableTime: true,
                                        noCalendar: false,
                                        dateFormat: "Y-m-d H:i",
                                        time_24hr: true,
                                    });
                                    flatpickr('#event_end', {
                                        enableTime: true,
                                        noCalendar: false,
                                        dateFormat: "Y-m-d H:i",
                                        time_24hr: true,
                                    });
                                </script>
                            </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                                <span class="circle">2</span>
                                <span class="label">Description</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                <div class="form-group">
                                    <label for="">Use Markdown</label>
                                    <textarea id="contentMD" name="description" class="w-75">{{$event->description}}</textarea>
                                    <script>
                                        var simplemde = new EasyMDE({ element: document.getElementById("contentMD"), toolbar: false });
                                    </script>
                                </div>
                            </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                                <span class="circle">3</span>
                                <span class="label">Image</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                @if ($event->image_url)
                                <img src="{{$event->image_url}}" alt="" class="img-fluid w-50 img-thumbnail">
                                @else
                                No image.
                                @endif
                                <p>An image can be displayed for the event. Please ensure we have the right to use the image, and that it is of an acceptable resolution. Make sure the image has no text or logos on it.</p>
                                <div class="input-group pb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image">
                                        <label class="custom-file-label">Choose image</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="active">
                            <a href="#!">
                                <span class="circle">4</span>
                                <span class="label">Options</span>
                            </a>
                            <div class="step-content w-75 pt-0">
                                <div class="form-group">
                                    <div class="mr-2">
                                        <input type="checkbox" class="" name="openControllerApps" id="openControllerApps">
                                        <label class="" for="">Open controller applications</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->editEventErrors->any())
<script>
    $("#editEvent").modal('show');
</script>
@endif

<!--End edit event modal-->

<!--create update modal-->
<div class="modal fade" id="createUpdate" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create event update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('events.admin.update.post', $event->slug)}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($errors->createUpdateErrors->any())
                    <div class="alert alert-danger">
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->createUpdateErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="updateTitle" id="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Use Markdown</label>
                        <textarea id="updateContent" name="updateContent"></textarea>
                        <script>
                            var simplemde = new EasyMDE({ element: document.getElementById("updateContent"), toolbar: false });
                        </script>
                    </div>
                    <div class="form-group">
                        <div class="mr-2">
                            <input type="checkbox" class="" name="announceDiscord" id="announceDiscord">
                            <label class="" for="">Announce on Discord</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->createUpdateErrors->any())
<script>
    $("#createUpdate").modal('show');
</script>
@endif

<!--End app update modal-->

@endcan

@endsection
