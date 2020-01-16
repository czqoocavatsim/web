@extends('layouts.master')

@section('navbarprim')

    @parent

@stop
@section('title', 'Position Report Tool - ')
@section('description', 'Generate position tools')
@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.toolSidebar')
        </div>
        <div class="col-md-9">
            <h1 class="font-weight-bold blue-text">Position Report Generator</h1>
            <hr>
            <div class="form-row">
                <div class="form-group col">
                    <label>Callsign</label>
                    <input id="callsignB" onblur="this.value = this.value.toUpperCase()" type="text" class="form-control" placeholder="UAE203">
                </div>
                <div class="form-group col">
                    <label>Reporting At</label>
                    <input id="reportingB" onblur="this.value = this.value.toUpperCase()" maxlength="7" type="text" class="form-control" placeholder="ELSIR">
                </div>
                <div class="form-group col">
                    <label>Time</label>
                    <input id="timeB" maxlength="4" onblur="this.value = this.value.toUpperCase()" type="text" class="form-control" placeholder="1512">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label>Flight Level</label>
                    <input id="flightLevelB" maxlength="5" onblur="this.value = this.value.toUpperCase()" type="text" class="form-control" placeholder="380">
                </div>
                <div class="form-group col">
                    <label>Mach Speed</label>
                    <input id="machB" onblur="this.value = this.value.toUpperCase()" maxlength="4" type="text" class="form-control" placeholder=".85">
                </div>
                <div class="form-group col">
                    <label>Next Fix</label>
                    <input id="nextB" maxlength="7" onblur="this.value = this.value.toUpperCase()" type="text" class="form-control" placeholder="50N/20W">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label>Estimating Next Fix</label>
                    <input onblur="this.value = this.value.toUpperCase()" id="estimatingB" maxlength="4" type="text" class="form-control" placeholder="0024">
                    <small class="text-muted">(Time passing next fix)</small>
                </div>
                <div class="form-group col">
                    <label>Fix Thereafter</label>
                    <input onblur="this.value = this.value.toUpperCase()" id="thereafterB" type="text" maxlength="7" class="form-control" placeholder="54N30W">
                    <small class="text-muted">If this fix is outside the OCA, type 'Domestic'.</small>
                </div>
            </div>
            <div class="form-row">
                <button type="button" onclick="generate()" class="btn btn-primary">Submit</button>
            </div><br/>
            <div id="errorA" class="alert alert-dismissible  alert-danger" role="alert" style="display:none;">
                <h4 id="errorHeading" class="alert-heading">Please fill the following fields:</h4>
                <p id="errorContent"></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <p class="border" id="results" style="padding: 1rem;">No results yet.</p>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="routingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Should I pick a NAT track or random routing?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <p>When planning your flight, you may have planned it using one of the current <a href="{{URL('/pilots/tracks')}}">NAT tracks</a> currently active.
                If you have used one of the NAT tracks to plan your oceanic flight, select <b>NAT track.</b><br/><br/>
                If you have <i>not</i> used a NAT track to plan your flight, please select <b>random routing.</b> This could include automatically generated PFPX routes if you don't have a NAT track source in the program.
            </p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>
<script src="{{ asset('js/positionReport.js') }}"></script>
@stop
