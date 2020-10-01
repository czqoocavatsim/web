@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="font-weight-bold blue-text pb-2">Solo Certifications</h1>
<ul class="list-unstyled mt-2 mb-0">
    <li class="mb-2">
        <a href="#" data-target="#addSoloCertificationModal" data-toggle="modal" style="text-decoration:none;"><span class="blue-text"><i class="fas fa-chevron-right"></i></span> &nbsp; <span class="black-text">Add solo certification</span></a>
    </li>
</ul>
<table class="table dt table-hover table-bordered">
    <thead>
        <th>CID</th>
        <th>Name</th>
        <th>Expires</th>
    </thead>
    <tbody>
        @foreach ($certs as $c)
            <tr>
                <th scope="row" class="font-weight-bold"><a href="#">{{$c->rosterMember->cid}}</a></th>
                <td>
                    {{$c->rosterMember->user->fullName('FL')}}
                </td>
                <td>
                    {{$c->expires->toDateString()}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!--Start add solo certification modal-->
<div class="modal fade" id="addSoloCertificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add solo certification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('training.admin.solocertifications.add')}}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->addSoloCertificationErrors->any())
                    <div class="alert alert-danger">
                        <h4>There were errors</h4>
                        <ul class="pl-0 ml-0 list-unstyled">
                            @foreach ($errors->addSoloCertificationErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="">Controller</label>
                        <select name="roster_member" id="" class="custom-select">
                            <option hidden>Please choose one....</option>
                            @foreach($trainingControllers as $c)
                                <option value="{{$c->id}}">{{$c->user->fullName('FLC')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Expiry date</label>
                        <input type="datetime" name="expires" class="form-control flatpickr" id="expires">
                        <script>
                            flatpickr('#expires', {
                                enableTime: false,
                                noCalendar: false,
                                dateFormat: "Y-m-d",
                                minDate: "{{Carbon\Carbon::now()->addDays(1)}}",
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
            </form>
        </div>
    </div>
</div>
<!--End add solo certification modal-->


<script>
    $("blockquote").addClass('blockquote');

    $(document).ready(function () {
        $('.table.dt').DataTable();
    })

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }

    if ($.urlParam('addSoloCertificationModal') == '1') {
        $("#addSoloCertificationModal").modal();
    }
</script>

@endsection
