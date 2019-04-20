<html>
    <head>
        <!--Metadata-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Rich Preview Meta-->
        <title>CZQO Gander Oceanic FIR</title>
        <meta name="description" content="Website for the VATSIM Gander Oceanic FIR">
        <meta name="theme-color" content="#3c75d1">
        <meta name="og:title" content="CZQO VATSIM">
        <meta name="og:description" content="Gander Oceanic FIR">
        <meta name="og:image" content="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!--Bootstrap-->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link href="{{ URL::to('/')}}/css/structure.css" rel="stylesheet">
        <link href="{{ URL::to('/')}}/css/czqo.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
        
    </head>
    <div class="page-wrap d-flex flex-row align-items-center" style="height: 100%; z-index: 44;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <span class="display-4 d-block">@yield('error')</span>
                    <br/>
                    <div class="mb-4 lead ">@yield('message')</div>
                    <a href="/" class="btn btn-link">Go home</a>
                </div>
            </div>
        </div>
    </div>

</html>