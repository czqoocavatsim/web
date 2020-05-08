<p>Generators</p>
<div class="list-group">
    <a href="{{url('/pilots/oceanic-clearance')}}" class="list-group-item list-group-item-action {{ Request::is('pilots/oceanic-clearance') ? 'active' : '' }}">
        Oceanic Clearance
    </a>
    <a href="{{url('/pilots/position-report')}}" class="list-group-item list-group-item-action {{ Request::is('pilots/position-report') ? 'active' : '' }}">
        Position Report
    </a>
</div><br/>
<p>Resources</p>
{{-- --}}
<div class="list-group">
    <a href="https://www.vatsim.net/pilots/resources" target="_blank" class="list-group-item list-group-item-action {{ Request::is('pilots/vatsim-resources') ? 'active' : '' }}">
        VATSIM Resources
    </a>
    <a href="https://nattrak.vatsim.net" target="_blank" class="list-group-item list-group-item-action">
        natTRAK
    </a>
    {{--<a href="{{url('/pilots/tutorial')}}" class="list-group-item list-group-item-action {{ Request::is('pilots/tutorial') ? 'active' : '' }}">
        Oceanic Tutorial
    </a>--}}
    <a href="{{url('/pilots/tracks')}}" class="list-group-item list-group-item-action {{ Request::is('pilots/tracks') ? 'active' : '' }}">
        Current NAT Tracks
    </a>
    <a href="{{url('/map')}}" class="list-group-item list-group-item-action">Map</a>
</div>
<br/>
<button data-toggle="modal" type="button" data-target="#shareModal" class="btn btn-success btn-block"><i class="fa fa-share-square"></i>&nbsp;Share these tools</button>
<!-- Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Share these tools</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <ul class="list-group-flush">
                <li class="list-group-item">
                    Tools home: <a href="https://vats.im/czqotools">vats.im/czqotools</a>
                    &nbsp;
                </li>
                <li class="list-group-item">
                    Oceanic clearance: <a href="https://vats.im/czqoclearance">vats.im/czqoclearance</a>
                    &nbsp;
                </li>
                <li class="list-group-item">
                    Position report: <a href="https://vats.im/czqoposrep">vats.im/czqoposrep</a>
                    &nbsp;
                </li>
            </ul>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>
