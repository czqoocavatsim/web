@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
<div class="container" style="margin-top: 20px;">
        <h2>Sector Files</h2>
        <h5>There are two methods to install CZQO Euroscope sector files:</h5>
        <div class="row">
            <div class="col">
                <div class="card">
                    <h5 class="card-header">Package Installer</h5>
                    <div class="card-body">
                      <p class="card-text">The preferred method is to install the CZQO sector files using the Euroscope package installer. This will install the files to <code>Documents\EuroScope\Gander-master</code>.
                        Included is a utility to insert your VATSIM login details into the profile to make it easier to connect.</p>
                      <p>If you have any issues or need support, please send an email directly to <a href="mailto:l.downes@vatpac.org">Liesel Downes.</a></p>
                      <a href="https://vatscratch.com/czqoes1810.exe" target="_blank" class="btn btn-primary">Download (.exe, approx 47 MB)</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <h5 class="card-header">Manual Installation</h5>
                    <div class="card-body">
                        <p class="card-text">If you are having issues, you can manually install the files by directly downloading them from GitHub. Follow the tutorial in README.md.</p>
                        <a href="https://github.com/chrissKLO/Gander" target="_blank" class="btn btn-primary">Open GitHub Repo</a>
                    </div>
                </div>
            </div>
        </div><br/>
        <p>Special thanks to <b>Chriss Klosowski</b> for updating our sector files!</p>
    </div>
@stop