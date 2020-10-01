@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.roster')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Roster</a>
<h2 class="blue-text font-weight-bold mt-2 pb-2">{{$rosterMember->user->fullName('FLC')}}</h2>
<div class="row">
    <div class="col-sm">
        <h5>Rating & Division</h5>
        <ul class="list-unstyled">
            <li>Subdivision: {{$rosterMember->user->subdivision_code ? $rosterMember->user->subdivision_name.'('.$rosterMember->user->subdivision_code.')' : 'None'}}</li>
            <li>Division: {{$rosterMember->user->division_name}} ({{$rosterMember->user->division_code}})</li>
            <li>Region: {{$rosterMember->user->region_name}} ({{$rosterMember->user->region_code}})</li>
            <li>Rating: {{$rosterMember->user->rating_GRP}} ({{$rosterMember->user->rating_short}})</li>
        </ul>
        <h5>Actions</h5>
        <ul class="list-unstyled mt-2 mb-0">
            <li class="mb-2">
                <a href="#" data-target="#editRosterMemberModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Edit controller</span></a>
            </li>
            <li class="mb-2">
                <a href="#" data-target="#removeRosterMemberModal" data-toggle="modal" style="text-decoration:none;"><span class="red-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Remove from roster</span></a>
            </li>
        </ul>
    </div>
    <div class="col-sm">
        <h5>Status</h5>
        <h3>
            @switch ($rosterMember->certification)
            @case("certified")
            <span class="badge badge-success rounded shadow-none">
                <i class="fa fa-check"></i>&nbsp;
                Certified
            </span>
            @break
            @case("not_certified")
            <span class="badge badge-danger rounded shadow-none">
                <i class="fa fa-times"></i>&nbsp;
                Not Certified
            </span>
            @break
            @case("training")
            <span class="badge badge-warning rounded shadow-none">
                <i class="fa fa-book-open"></i>&nbsp;
                Training
            </span>
            @break
            @default
            <span class="badge badge-dark rounded shadow-none">
                <i class="fa fa-question"></i>&nbsp;
                Unknown
            </span>
            @endswitch
        </h3>
        <h3>
            @switch ($rosterMember->active)
            @case(true)
            <span class="badge badge-success rounded shadow-none">
                <i class="fa fa-check"></i>&nbsp;
                Active
            </span>
            @break
            @case(false)
            <span class="badge badge-danger rounded shadow-none">
                <i class="fa fa-times"></i>&nbsp;
                Inactive
            </span>
            @break
            @endswitch
        </h3>
    </div>
</div>

<!--Delete modal-->
<div class="modal fade" id="removeRosterMemberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>This will soft delete the controller from the roster, meaning they're not visible, but still in the database.</p>
                <img src="https://tenor.com/view/bartsimpson-boot-simpsons-thesimpsons-homer-gif-9148667.gif" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Dismiss</button>
                <a href="{{route('training.admin.roster.removecontroller', $rosterMember->cid)}}" role="button" class="btn btn-danger">Remove</a>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End delete modal-->


<!--Start edit roster member modal-->
<div class="modal fade" id="editRosterMemberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit roster member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.roster.editcontroller', $rosterMember->cid)}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->editRosterMemberErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->editRosterMemberErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Certification</label>
                        <select class="custom-select" aria-valuemax="{{$rosterMember->certification}}" name="certification">
                            <option value="not_certified" selected>Not Certified</option>
                            <option value="certified">Certified</option>
                            <option value="training">Training</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Active</label>
                        <select value="{{$rosterMember->active}}" class="custom-select" name="active">
                            <option value="true" selected>Active</option>
                            <option value="false">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Date certified (ignored if not certified/training)</label>
                        <input value="{{$rosterMember->date_certified}}" type="datetime" name="date_certified" class="form-control flatpickr" id="date_certified">
                        <script>
                            flatpickr('#date_certified', {
                                enableTime: false,
                                noCalendar: false,
                                dateFormat: "Y-m-d",
                                defaultDate: "{{Carbon\Carbon::now()}}"
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="">Remarks</label>
                        <input type="text" value="{{$rosterMember->remarks}}" name="remarks" id="" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Edit">
                </div>
            </form>
        </div>
    </div>
</div>
<!--End edit roster member modal-->


@endsection
