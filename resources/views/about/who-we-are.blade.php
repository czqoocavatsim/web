@extends('layouts.master')
@section('title', 'Who We Are - ')
@section('content')
<div class="jarallax card card-image shadow-none rounded-0" data-jarallax data-speed="0.2" style="height: 60vh">
    <img class="jarallax-img" src="https://cdn.discordapp.com/attachments/498332235154456579/695982036346994708/unknown.png" alt="">
    <div class="mask flex-center flex-column" style="height: 100%; width: 100%; background: linear-gradient(90deg,rgba(255, 255, 255, 1),rgba(255, 255, 255, 0.9),rgba(0, 110, 255, 0.664))!important;">
        <div class="container">
            <div class="py-5" style="width: 50%;">
                <h1 class="h1 my-4 py-2 font-weight-bold blue-text" style="font-size: 3em; color: #fff;">We provide ocenaic control services over the skies of the North Atlantic on VATSIM.
                </h1>
                <p style="font-size: 1.4em;" class="mt-3">
                    As a member of the VATCAN (Canada) Division, Gander Oceanic is dedicated to providing control services, training, and resources for flying and controlling in the Gander and Shanwick OCAs.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <h3 class="font-weight-bold blue-text">The Gander and Shanwick OCAs</h3>
            <p>The Gander OCA spans a massive 905 nautical miles, bordering Shanwick at 30 degrees west, Santa Maria at 45 degrees north (between 30 and 40 degrees west) and New York Oceanic at 45 degrees north (between 40 and 51 degrees west). The OCA also borders several domestic flight information regions (FIRs), including Gander Domestic, Montreal, Sondrestrom and Edmonton. Airspace within the Gander OCA is Class A above 5,500 ft and Class G below.</p>
            <p>The Shanwick OCA stretches another enormous 545 nautical miles, bordering Gander at 30 degrees west, Santa Maria at 45 degrees north and Reykjavik at 61 degrees north. Like Gander, Shanwick also borders several domestic FIRs: Scottish, Shannon, London, Brest and Madrid. Airspace is Class A above 5,500 ft and below, Class G.</p>
            <p style="font-size: 1.2em;" class="mt-3">
                <a class="font-weight-bold text-body" href="https://knowledgebase.ganderoceanic.com/en/basics/airspace">Read more about it &nbsp;&nbsp;<i class="fas fa-arrow-right blue-text"></i></a>
            </p>
        </div>
        <div class="col-md-7">
            <script src="{{asset('js/leaflet.latlng.js')}}"></script>
            <div id="aboutPageMap" style="height:300px;">
            </div>
            <script>
                createAboutPageMap(null, null, null)
            </script>

        </div>
    </div>
</div>
@endsection
