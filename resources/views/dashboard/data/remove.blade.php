@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>GDPR Removal Request</h2><br>
        <p>Use this form to request removal of your data from the Gander Oceanic site. You may choose between soft delete and hard delete.<br/>
            <b>Hard delete: </b> remove all data pertaining to you, including training records.<br/>
            <b>Soft delete: </b> remove all data identifying you, but keep training records + VATSIM CID for our logging purposes.
        </p>
        <hr/>
        @if ($canremove == "false")
            <div class="alert alert-danger">
                You may not remove your data through this method because you are a staff member. Please email the FIR Chief.
            </div>
        @else
            {{ Form::open(['route' => 'data.remove.store'] )}}
            <label>Delete level</label>
            <div class="form-check">
                {{ Form::radio('deleteMethod', 'hard', false, ['class' => 'form-check-input']) }}<label class="form-check-label">Hard delete</label>
            </div>
            <div class="form-check">
                {{ Form::radio('deleteMethod', 'soft', false, ['class' => 'form-check-input']) }}<label class="form-check-label">Soft delete</label>
            </div>
            <br/>
            <div class="form-group">
                <label>Verify your email address</label>
                {{ Form::email('email', false, ['class' => 'form-control']) }}
                <small class="text-muted">Please enter the email address currently associated with your VATSIM account. We require this for verification purposes and we will not store this information.</small>
            </div>
            <br/>
            <div class="form-group">
                <a href="javascript:displayModal()" role="button" class="btn btn-danger">Request Removal Under GDPR</a>
            </div>
        @endif
    <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Request Removal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4 style="font-weight: bolder;">Are you sure you wish to do this?</h4>
                        <p>The following consequences will occur:
                        <ul style="list-style: decimal;">
                            <li>Your data will be <i>removed permanently.</i></li>
                            <li>Training records may be <i>wiped.</i></li>
                            <li>Your roster status may be <i>wiped.</i></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        {{ Form::submit('I am sure. Please remove my data', ['class' => 'btn btn-danger']) }}
                        <a role="button" href="javascript:displayModal()" class="btn btn-outline-secondary" >I have changed my mind. Please do not remove my data</a>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}

        <script>
            function displayModal() {
                $('#deleteModal').modal('toggle')
            }
        </script>
    </div>
@stop