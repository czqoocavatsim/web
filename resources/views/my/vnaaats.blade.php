@extends('layouts.primary')

@section('content')
<div class="container py-4">
    <a href="{{route('my.index')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> myCZQO</a>
    <h1 class="font-weight-bold blue-text">vNAAATS key</h1>
    <p style="font-size: 1.2em;">Your personal key for the vNAAATS EuroScope plugin and your password for the vNAAATS Flight Data Display.</p>
    <hr>

    @if(session('vnaaats_new'))
        @php($n = session('vnaaats_new'))
        <div class="alert alert-warning">
            <strong>Copy these now. They are shown once and cannot be shown again.</strong>
        </div>
        <h5 class="font-weight-bold blue-text">Plugin key</h5>
        <p>In <code>vnaaats.cfg</code> (next to VatsimNAAATS.dll) put this on the <code>key=</code> line, then restart EuroScope:</p>
        <pre class="p-3 border rounded" style="user-select: all;">key={{ $n['key'] }}</pre>
        <h5 class="font-weight-bold blue-text mt-4">Flight Data Display sign-in</h5>
        <p>At <a href="https://vnaaats.com/fdd" target="_blank">vnaaats.com/fdd</a> use your name or CID <strong>{{ $n['cid'] }}</strong> and this password:</p>
        <pre class="p-3 border rounded" style="user-select: all;">{{ $n['password'] }}</pre>
        <p>Valid until <strong>{{ \Carbon\Carbon::createFromTimestamp($n['expires'])->format('j F Y') }}</strong> (90 days). Come back to this page to get a new one. Do not share them: they are yours.</p>
    @elseif(!$eligible)
        <div class="alert alert-info">vNAAATS keys are for active, certified Gander Oceanic controllers. Your roster status does not currently qualify. If you think this is wrong, contact the Gander Oceanic staff.</div>
    @elseif(!$configured)
        <div class="alert alert-info">vNAAATS keys are not set up on this website yet.</div>
    @else
        @if($info && ($info['exists'] ?? false) && !($info['revoked'] ?? false) && !($info['expired'] ?? false))
            <p>You have a key. It expires on <strong>{{ \Carbon\Carbon::createFromTimestamp((int) $info['expires'])->format('j F Y') }}</strong>.@if(!empty($info['lastUsed'])) Last used {{ \Carbon\Carbon::createFromTimestamp((int) $info['lastUsed'])->diffForHumans() }}.@endif</p>
            <p>Lost it, or want a fresh one? Requesting a new key replaces the old key and password straight away.</p>
        @elseif($info && ($info['exists'] ?? false))
            <p>Your last key has expired or been withdrawn. Request a new one below.</p>
        @else
            <p>You do not have a key yet.</p>
        @endif
        <form method="POST" action="{{ route('my.vnaaats.issue') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Request a new vNAAATS key</button>
        </form>
        <p class="mt-3 text-muted">Keys last 90 days and stop working if you are no longer active and certified. The key and password are created on the vNAAATS server and are only shown to you once.</p>
    @endif
</div>
@endsection
