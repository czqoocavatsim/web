@extends('layouts.master')
@section('title', 'Who We Are - ')
@section('content')
<div class="d-none d-md-block jarallax card card-image shadow-none rounded-0" data-jarallax data-speed="0.2" style="height: 60vh">
    <img class="jarallax-img" src="https://cdn.discordapp.com/attachments/498332235154456579/695982036346994708/unknown.png" alt="">
    <div class="mask flex-center flex-column" style="background: linear-gradient(90deg,rgba(255, 255, 255, 1),rgba(255, 255, 255, 0.9),rgba(0, 110, 255, 0.664))!important;">
        <div class="container">
            <div class="py-5" style="width: 50%;">
                <h1 class="h1 my-4 py-2 font-weight-bold blue-text" style="font-size: 3em; color: #fff;">We provide oceanic control services over the skies of the North Atlantic on VATSIM.
                </h1>
                <p style="font-size: 1.4em;" class="mt-3">
                    As a member of the VATCAN (Canada) Division on VATSiM, Gander Oceanic is dedicated to providing control services, training, and resources for flying and controlling in the Gander and Shanwick OCAs.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid blue d-md-none">
    <div class="container py-5 white-text blue">
        <h1 class="h1 my-4 py-2 font-weight-bold" style="font-size: 3em; color: #fff;">We provide oceanic control services over the skies of the North Atlantic on VATSIM.
        </h1>
        <p style="font-size: 1.4em;" class="mt-3">
            As a member of the VATCAN (Canada) Division on VATSIM, Gander Oceanic is dedicated to providing control services, training, and resources for flying and controlling in the Gander and Shanwick OCAs.
        </p>
    </div>
</div>
<div class="container pt-5 pb-4">
    <h3 class="font-weight-bold blue-text">The Gander Oceanic OCA  </h3>
    <div class="row">
        <div class="col-md">
            <p>
                The Gander Oceanic OCA has been committed to providing the best oceanic control available on the VATSIM network for many years. Supported by VATCAN and the Americas region, our team of is dedicated to the task of providing quality training of non-radar surveillance procedures and phraseology over Gander and Shanwick, supporting our incredible community of talented controllers and providing resources for pilots so that everybody can experience the best that oceanic flight has to offer.
            </p>
        </div>
        <div class="col-md">
            <p>
                Since 2017, the Gander Oceanic OCA has undergone a major revitalisation, with the installment of a fresh staff team, development of a brand new website and a more concentrated focus on the quality of our training program and available resources. New procedures are constantly being reviewed and our tools are regularly updated based on the amazing feedback from our community. We aim to provide the coolest, calmest and most collected service to pilots flying over the North Atlantic, and we're glad that you're interested in how it all works!
            </p>
        </div>
    </div>
</div>
<div class="container py-2">
    <div class="row text-right">
        <div class="col-md-7">
            <script src="{{asset('js/leaflet.latlng.js')}}"></script>
            <div id="aboutPageMap" style="height:300px;" class="mb-3">
            </div>
            <script>
                createAboutPageMap(null, null, null)
            </script>
            <style>
                .leaflet-tooltip {
                position: absolute;
                padding: 6px;
                background: none !important;
                border: none !important;
                border-radius: none !important;
                color: #222;
                white-space: nowrap;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                pointer-events: none;
                box-shadow: none !important;
                }
                .leaflet-tooltip-top:before, .leaflet-tooltip-bottom:before, .leaflet-tooltip-left:before, .leaflet-tooltip-right:before {
                position: absolute;
                pointer-events: none;
                border: none !important;
                background: transparent;
                content: "";
                }
            </style>
        </div>
        <div class="col-md-5">
            <h3 class="font-weight-bold blue-text">The Gander and Shanwick OCAs</h3>
            <p>The Gander OCA spans a massive 905 nautical miles, bordering Shanwick at 30 degrees west, Santa Maria at 45 degrees north (between 30 and 40 degrees west) and New York Oceanic at 45 degrees north (between 40 and 51 degrees west). The OCA also borders several domestic flight information regions (FIRs), including Gander Domestic, Montreal, Sondrestrom and Edmonton. Airspace within the Gander OCA is Class A above 5,500 ft and Class G below.</p>
            <p>The Shanwick OCA stretches another enormous 545 nautical miles, bordering Gander at 30 degrees west, Santa Maria at 45 degrees north and Reykjavik at 61 degrees north. Like Gander, Shanwick also borders several domestic FIRs: Scottish, Shannon, London, Brest and Madrid. Airspace is Class A above 5,500 ft and below, Class G.</p>
            <p style="font-size: 1.2em;" class="mt-3">
                <a class="font-weight-bold text-body" href="https://knowledgebase.ganderoceanic.com/en/basics/airspace">Read more about it &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
            </p>
        </div>
    </div>
</div>
@endsection
