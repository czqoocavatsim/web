<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--
        {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_name}}
        {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})
        Built on Bootstrap 4 and Laravel 6

        Written by Liesel D

          sSSs. sSSSSSs   sSSSs     sSSSs
         S           s   S     S   S     S
        S           s   S       S S       S
        S          s    S       S S       S
        S         s     S       S S       S
         S       s       S   s S   S     S
          "sss' sSSSSSs   "sss"ss   "sss"

        For Flight Simulation Use Only - Not To Be Used For Real World Navigation. All content on this web site may not be shared, copied, reproduced or used in any way without prior express written consent of Gander Oceanic. © Copyright {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic, All Rights Reserved.

        Taking a peek under the hood, and like what you see? Want to help out? Send Liesel an email!
        -->
        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--Rich Preview Meta-->
        <title>Map - Gander Oceanic VATSIM</title>
        <meta name="theme-color" content="#000000">
        <meta name="og:title" content="Map - Gander Oceanic VATSIM">
        <meta name="og:image" content="@yield('image',asset('img/icon.png'))">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.3/materia/bootstrap.min.css" rel="stylesheet" integrity="sha384-5bFGNjwF8onKXzNbIcKR8ABhxicw+SC1sjTh6vhSbIbtVgUuVTm2qBZ4AaHc7Xr9" crossorigin="anonymous">        <!-- Material Design Bootstrap -->
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
        <!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
        <!--CZQO specific CSS-->
        <link href="{{ asset('css/czqomd.css') }}" rel="stylesheet">
        <!--Leaflet-->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
        <script src="{{asset('/js/leaflet.rotatedMarker.js')}}"></script>
    </head>
    <body>
        <div id="map" style="height: 100%; margin:0; background:#000; z-index: 0 !important; position: relative;">
            <div class="container flex-center">
                <h5 style="color:#fff;"><i class="fas fa-circle-notch fa-spin"></i>
                    &nbsp;
                    Loading map...
                </h5>
            </div>
        </div>
        <script src="{{asset('js/bigmap.js')}}"></script>
        <script>
            createBigMap(@php echo json_encode($planes); @endphp, @php echo json_encode($ganderControllers->toArray()); @endphp, @php echo json_encode($shanwickControllers->toArray()); @endphp);
        </script>
        <!-- Frame Modal Bottom -->
        <div class="modal fade bottom" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">

        <!-- Add class .modal-frame and then add class .modal-bottom (or other classes from list above) to set a position to the modal -->
        <div class="modal-dialog modal-frame modal-bottom" role="document">


        <div class="modal-content">
            <div class="modal-body">
            <div class="row d-flex justify-content-center align-items-center">

                <p class="pt-3 pr-2">This map updates approx. every 15 minutes with VATSIM network data, NAT tracks, and online sectors. It should not be taken as an up to date reflection of VATSIM.
                </p>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>
        </div>
        <!-- Frame Modal Bottom -->
        <script>
            $("#modal").modal();
        </script>
    </body>
</html>
