@extends('layouts.master')
@section('content')
<div class="container py-4">
    <a href="{{route('events.admin.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Events</a>
    <h1 class="font-weight-bold blue-text">Create Event</h1>
    <hr>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('events.admin.create.post')}}" enctype="multipart/form-data">
            @csrf
            @if($errors->createEventErrors->any())
            <div class="alert alert-danger">
                <h4>There were errors creating the event</h4>
                <ul class="pl-0 ml-0 list-unstyled">
                    @foreach ($errors->createEventErrors->all() as $error)
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
                            <input type="text" name="name" id="" class="form-control" placeholder="Staff Up Saturdays">
                        </div>
                        <div class="form-group">
                            <label for="">Start date and time</label>
                            <input type="datetime" name="start" placeholder="Put event start date/time here" class="form-control flatpickr" id="event_start">
                        </div>
                        <div class="form-group">
                            <label for="">End date and time</label>
                            <input type="datetime" name="end" placeholder="Put event end date/time here" class="form-control flatpickr" id="event_end">
                        </div>
                        <div class="form-group">
                            <label for="">Departure airport ICAO (optional)</label>
                            <input maxlength="4" type="text" name="departure_icao" id="" class="form-control" placeholder="CYYC">
                        </div>
                        <div class="form-group">
                            <label for="">Arrival airport ICAO (optional)</label>
                            <input maxlength="4" type="text" name="arrival_icao" id="" class="form-control" placeholder="EIDW">
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
                            <textarea id="contentMD" name="description" class="w-75"></textarea>
                            <script>
                                var simplemde = new SimpleMDE({ element: document.getElementById("contentMD"), toolbar: false });
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
                        <p>An image can be displayed for the event. Please ensure we have the right to use the image, and that it is of an acceptable resolution. Make sure the image has no text or logos on it.</p>
                        <div class="input-group pb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image">
                                <label class="custom-file-label">Choose image</label>
                            </div>
                        </div>
                        <p>Alternatively, pick an already uploaded image.</p>
                        <button class="btn" type="button" data-toggle="collapse" data-target="#collapseExample">
                            Pick uploaded image
                        </button>
                        <div class="collapse" id="collapseExample">
                            <div class="mt-3">
                                <select name="uploadedImage" class="image-picker masonry">
                                    <option value=""></option>
                                    @foreach($uploadedImgs as $img)
                                    <option data-img-src="{{$img->path}}" data-img-class="img-fluid" data-img-alt="{{$img->id}}" value="{{$img->id}}">  {{$img->id}}  </option>
                                    @endforeach
                                  </select>
                                  <script>
                                    $("select.image-picker").imagepicker()
                                  </script>
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
            <input type="submit" value="Create Event" class="btn btn-primary">
        </form>
        </div>
    </div>
</div>
@endsection
