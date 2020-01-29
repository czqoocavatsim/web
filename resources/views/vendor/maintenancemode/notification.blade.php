{{--
    This is a simple notification bar to users who are exempt from the maintenance page, telling them that a
    maintenance period is going on. You should place this within your main template(s) via a call to:

    @include('maintenancemode::notification')
--}}

@if(isset(${Config::get('maintenancemode.inject.prefix').'Enabled'}) &&
    ${Config::get('maintenancemode.inject.prefix').'Enabled'} == true)
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
