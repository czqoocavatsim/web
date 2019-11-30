{{--
    This is a simple notification bar to users who are exempt from the maintenance page, telling them that a
    maintenance period is going on. You should place this within your main template(s) via a call to:

    @include('maintenancemode::notification')
--}}

@if(isset(${Config::get('maintenancemode.inject.prefix').'Enabled'}) &&
    ${Config::get('maintenancemode.inject.prefix').'Enabled'} == true)

    @if(Config::get('maintenancemode.notification-styles', true))
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Bootstrap core CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.1.3/materia/bootstrap.min.css" rel="stylesheet" integrity="sha384-5bFGNjwF8onKXzNbIcKR8ABhxicw+SC1sjTh6vhSbIbtVgUuVTm2qBZ4AaHc7Xr9" crossorigin="anonymous">        <!-- Material Design Bootstrap -->
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
    @endif

    <div class="maintenance-mode-alert alert red mb-0 d-flex flex-row" style="border-radius:0;" id="maintenance-mode-alert" role="alert">
        <div class="container">
            <strong>@lang('maintenancemode::defaults.title'):&nbsp;</strong>

            {{-- Show the truncated message (so it doesn't overflow) --}}
            @if(isset(${Config::get('maintenancemode.inject.prefix').'Message'}))
                <span title="{{ ${Config::get('maintenancemode.inject.prefix').'Message'} }}">
                    {{ Str::limit(${Config::get('maintenancemode.inject.prefix').'Message'}, 100, "&hellip;") }}
                </span>
            @endif

            {{-- And show a human-friendly timestamp --}}
            @if(isset(${Config::get('maintenancemode.inject.prefix').'Timestamp'}) &&
                ${Config::get('maintenancemode.inject.prefix').'Timestamp'} instanceof DateTime)

                <time class="float-right" datetime="{{ ${Config::get('maintenancemode.inject.prefix').'Timestamp'} }}" title="{{ ${Config::get('maintenancemode.inject.prefix').'Timestamp'} }}">
                    {{ ${Config::get('maintenancemode.inject.prefix').'Timestamp'}->diffForHumans() }}
                </time>

            @endif
        </div>
    </div>

@endif
