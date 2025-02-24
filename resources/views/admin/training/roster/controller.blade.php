@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.roster')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Roster</a>
<h2 class="blue-text mt-2 pb-2">{{$rosterMember->user->fullName('FLC')}}</h2>
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
        @php
            $currency = $rosterMember->currency;
            $class = $currency < 0.5 ? 'red' : ($currency < 6.0 ? 'blue' : 'green');
        @endphp

        <h3>
            <span style='font-weight: 400'
                class='badge rounded {{ $class }} text-white p-2 shadow-none'>

                @if($currency == 0)
                    <td class="bg-success text-white">
                        0m Recorded
                    </td>
                @elseif($currency < 1)
                    <td class="bg-success text-white">
                        {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m Recorded
                    </td>
                @else
                    <td class="bg-success text-white">
                        {{ floor($currency) }}h {{ str_pad(round(($currency - floor($currency)) * 60), 2, '0', STR_PAD_LEFT) }}m Recorded
                    </td>
                @endif
            </span>
        </h3> 
        <h3>
            {{$rosterMember->certificationLabelHtml()}}
        </h3>
        <h3>
            {{$rosterMember->activeLabelHtml()}}
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-lg">
    <h3 class="font-weight-bold blue-text mt-4 pb-2">Controller Connections</h3>
    <p class="mt-2">List of {{$rosterMember->user->fullName('F')}}'s connections over the last 12 Months.</p>
    {{-- <p class="mt-0">Connections less than 30 minutes are shown in red, and do not count towards Controller Currency.</p> --}}
    <p class="mt-0">Connections of less than 30 minutes will show with a <i style="color: red;" class="fas fa-times"></i> within the time collum.</p>
    <table id="dataTable" class="table table-hover">
        <thead>
            <th>Position</th>
            <th>Logon</th>
            <th>Logoff</th>
            <th>Time</th>
        </thead>
        <tbody>
            @foreach ($sessions as $s)
                <tr>
                    <th>{{$s->callsign}}</th>
                    <th>{{\Carbon\Carbon::parse($s->session_start)->format('m/d/Y \a\t Hi\Z')}}</th>
                    <th>
                        @if($s->session_end === null)
                        Currently Connected
                        @else
                        {{\Carbon\Carbon::parse($s->session_end)->format('m/d/Y \a\t Hi\Z')}}
                        @endif
                    </th>
                    @if($s->duration < 0.5)
                        <td>
                            {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m <i style="color: red;" class="fas fa-times"></i>
                        </td>
                    @else
                        @if($s->duration < 1)
                            <td>
                                {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m
                            </td>
                        @else
                            <td>
                                {{ floor($s->duration) }}h {{ str_pad(round(($s->duration - floor($s->duration)) * 60), 2, '0', STR_PAD_LEFT) }}m
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
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
                            @if ($rosterMember->certification == 'certified')
                            <option value="not_certified">Not Certified</option>
                            <option value="certified" selected>Certified</option>
                            <option value="training">Training</option>
                            @elseif ($rosterMember->certification == 'not_certified')
                            <option value="not_certified" selected>Not Certified</option>
                            <option value="certified">Certified</option>
                            <option value="training">Training</option>
                            @elseif ($rosterMember->certification == 'training')
                            <option value="not_certified">Not Certified</option>
                            <option value="certified">Certified</option>
                            <option value="training" selected>Training</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Active</label>
                        <select value="{{$rosterMember->active}}" class="custom-select" name="active">
                            @if ($rosterMember->active == true)
                            <option value="true" selected>Active</option>
                            <option value="false">Inactive</option>
                            @else
                            <option value="true">Active</option>
                            <option value="false" selected>Inactive</option>
                            @endif
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
                                defaultDate: "{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rosterMember->date_certified ?? Carbon\Carbon::now())}}"
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

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    } );
</script>


@endsection
