@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
    <h2>News and Announcements</h2><br/>
    <div class="row">
        <div class="col">
            <h4 style="">News</h4>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" href="{{url('/dashboard/news/create')}}" >Create Item</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Promotions</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if (count($news) < 1)
                        <br/>
                        <p>None found.</p>
                    @else
                        <br/>
                        <table id="newsTable" class="table table-hover">
                            <thead>
                                <th scope="col">Title</th>
                                <th scope="col">Published</th>
                                <th scope="col">View</th>
                            </thead>
                            <tbody>
                                @foreach ($news as $article)
                                    <tr>
                                        <td>{{$article->title}}</td>
                                        <td>{{$article->date}}</td>
                                        <td><a href="{{url('/dashboard/news/article/'.$article->id)}}"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    @if (count($promotions) < 1)
                        <br/>
                        <p>None found.</p>
                    @else
                        <br/>
                        <table id="promotionsTable" class="table table-hover">
                            <thead>
                                <th scope="col">Title</th>
                                <th scope="col">Published</th>
                                <th scope="col">View</th>
                            </thead>
                            <tbody>
                                @foreach ($promotions as $article)
                                    <tr>
                                        <td>{{$article->title}}</td>
                                        <td>{{$article->date}}</td>
                                        <td><a href="{{url('/dashboard/news/article/'.$article->id)}}"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <br/>
            <h4>
                Home Page Carousel
                <a href="#" role="button" data-toggle="modal" data-target="#carouselModal" class="btn btn-sm btn-primary">Add Carousel Item</a>
            </h4>
            @if (count($carouselItems) < 1)
                <p>None found.</p>
            @else
                <h5>Current images:</h5>
                <table id="newsTable" class="table table-hover">
                    <thead>
                    <th scope="col">Image</th>
                    <th scope="col">Caption</th>
                    <th scope="col">URL</th>
                    <th scope="col">Delete</th>
                    </thead>
                    <tbody>
                    @foreach ($carouselItems as $item)
                        <tr>
                            <td style="word-wrap:  break-word; max-width: 12em;">
                                <a href="{{$item->image_url}}">{{$item->image_url}}</a>
                            </td>
                            <td style="word-wrap:  break-word; max-width: 12em;">{{$item->caption}}</td>
                            <td style="word-wrap:  break-word; max-width: 12em;">
                                <a href="{{$item->caption_url}}">{{$item->caption_url}}</a>
                            </td>
                            <td><a href="{{url('/dashboard/news/carousel/'.$item->id)}}"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col">
            @if (Auth::user()->permissions >= 4)
            <div class="card">
                <div class="card-header">
                    Send an Email
                </div>
                <div class="card-body">
                    <h5 class="card-title">Send an email announcement to all users</h5>
                    <p class="card-text">This will send an email to all users. <b>This will email ALL users regardless of email settings. Misuse of this under the GDPR will result in disciplinary actions.</b></p>
                    <a href="{{Url('/dashboard/news/announcement/emailannouncement')}}" class="btn btn-primary">Start</a>
                </div>
            </div>
            <br/>
            @endif
            @if (Auth::user()->permissions >= 4)
            <div class="card">
                <div class="card-header">
                    Site Banner
                </div>
                <div class="card-body">
                    <h5 class="card-title">Set Site Banner</h5>
                    <p class="card-text">This will create a banner at the top of every page with an announcement.</p>
                    <a href="#" role="button" data-toggle="modal" data-target="#bannerModal" class="btn btn-primary">Start</a>
                </div>
            </div>
            @endif
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set site banner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'news.setbanner']) !!}
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Content (be reasonable with the length)</label>
                    {!! Form::text('content', \App\CoreSettings::where('id', 1)->firstOrFail()->banner, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label class="col-form-label">Learn more URL</label>
                    {!! Form::text('url', \App\CoreSettings::where('id', 1)->firstOrFail()->bannerLink, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Mode</label>
                    {!! Form::select('mode', ['primary' => 'Primary (blue)', 'success' => 'Success (green)', 'info' => 'Info (purple)', 'warning' => 'Warning (yellow)', 'danger' => 'Danger (red)', 'dark' => 'Dark (dark gray)'],  \App\CoreSettings::where('id', 1)->firstOrFail()->bannerMode, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{url('/dashboard/news/removebanner')}}" class="btn btn-secondary">Remove Banner</a>
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="carouselModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add carousel item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Images MUST be 170 px high or below. This fits our OTS banner's aspect ratio. Use of the caption feature should be limited to images with no or very little text on them. The caption usually displays in the center of the image.
                </p>
                {!! Form::open(['route' => 'news.carousel.add']) !!}
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Image URL (required)</label>
                    {!! Form::text('image_url', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Caption</label>
                    {!! Form::text('caption', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Caption URL</label>
                    {!! Form::text('caption_url', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#newsTable').DataTable( {
            "order": [[ 1, "desc" ]]
        } );
        $('#promotionsTable').DataTable( {
            "order": [[ 1, "desc" ]]
        } );
    } );
</script>
@stop