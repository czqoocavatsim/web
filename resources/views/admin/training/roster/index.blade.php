@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Controller Roster</h1>
<ul class="list-unstyled mt-2 mb-0">
    <li class="mb-2">
        <a href="#" data-target="#addRosterMemberModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Add controller to roster</span></a>
    </li>
    <li class="mb-2">
        <a href="" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Export roster</span></a>
    </li>
</ul>
<table class="table dt table-hover table-bordered">
    <thead>
        <th>CID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Active</th>
    </thead>
    <tbody>
        @foreach ($roster as $r)
            <tr>
                <th scope="row" class="font-weight-bold"><a href="">{{$r->cid}}</a></th>
                <td>
                    {{$r->user->fullName('FL')}}
                    @if ($r->user_id == 2)
                        <i title="Not linked to a user account." class="fas fa-unlink"></i>
                    @endif
                </td>
                @if ($r->certification == "certified")
                    <td class="bg-success text-white">
                        Certified
                    </td>
                @elseif ($r->certification == "not_certified")
                    <td class="bg-danger text-white">
                        Not Certified
                    </td>
                @elseif ($r->certification == "training")
                    <td class="bg-warning text-dark">
                        Training
                    </td>
                @else
                    <td>
                        {{$r->certification}}
                    </td>
                @endif

                @if ($r->active)
                    <td class="bg-success text-white">
                        Active
                    </td>
                @else
                    <td class="bg-danger text-white">
                        Inactive
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function () {
        $('.table.dt').DataTable();
    })
</script>

<!--Start add roster member modal-->
<div class="modal fade" id="addRosterMemberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add roster member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Controller CID</label>
                    <input type="text" name="cid" maxlength="9" id="" class="form-control" placeholder="1300001">
                </div>
                <div class="form-group">
                    <label for="">Certification</label>
                    <select class="custom-select" name="certification">
                        <option value="not_certified" selected>Not Certified</option>
                        <option value="certified">Certified</option>
                        <option value="training">Training</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Active</label>
                    <select class="custom-select" name="active">
                        <option value="true" selected>Active</option>
                        <option value="false">Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Date certified</label>
                    <input type="datetime" name="date_certified" class="form-control flatpickr" id="date_certified">
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
                    <input type="text" name="remarks" id="" class="form-control" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Add">
            </div>
        </div>
    </div>
</div>
<!--End add roster member modal-->

@endsection
