@extends('layouts.master')

@section('title', 'OCA Map - ')
@section('description', 'Map of the Gander and Shanwick OCAs, with pilots, online sectors, and NAT Tracks updated every 15 minutes.')

@section('content')
    <script src="{{asset('js/leaflet.latlng.js')}}"></script>
    <div id="map" style="height: calc(100vh - 59px); ">
        <div class="container flex-left pt-5" style="z-index:999">
            <h1 class="font-weight-bold" style="opacity: 0.5">OCA Map</h1>
        </div>
    </div>

    <div class="modal fade bottom" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-center align-items-center">
                        <p class="pt-3 pr-2">This map updates approx. every 15 minutes with VATSIM network data, NAT tracks, and online sectors. It should not be taken as an up to date reflection of VATSIM.
                        </p>
                    </div>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <script>
        $("#modal").modal();
        createMap(@php echo json_encode($planes); @endphp, @php echo json_encode($ganderControllers->toArray()); @endphp, @php echo json_encode($shanwickControllers->toArray()); @endphp);
    </script>

@endsection
