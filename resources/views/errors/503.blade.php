<!DOCTYPE HTML>
<html lang="en">
    <head>
        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--Rich Preview Meta-->
        <title>@yield('title', 'Maintenance') - Gander Oceanic VATSIM</title>
        <meta name="og:image" content="@yield('image',asset('favicon.ico'))">
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
    </head>
    <div class="page-wrap d-flex flex-column align-items-center justify-content-center" style="height: 100%; z-index: 44;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <div class="mb-4 lead">Gander Oceanic OCA is currently offline for maintenance. If you need to reach us, <a href="mailto:chief@ganderoceanic.ca">email us</a> or contact us via Discord.
                    </div>
                </div>
            </div>
        </div>
        <div class="justify-self-bottom">
            <small class="text-muted">Copyright {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic - All Rights Reserved - {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_name}} {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->release}} ({{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->sys_build}})</small>
        </div>
    </div>
</html>
