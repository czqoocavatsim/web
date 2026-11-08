<span hidden data-online-callsigns="{{ $controllers->pluck('callsign')->implode(', ') }}"></span>
@if (count($controllers) < 1)
    <div class="zh-empty">No controllers are currently providing oceanic services.</div>
@else
    <div class="zh-table">
        <div class="zh-tr zh-th"><span class="c1">Callsign</span><span class="c2">Controller</span><span class="c3">Online</span></div>
        @foreach ($controllers as $controller)
            @php
                $d = $controller->session_start->diff(\Carbon\Carbon::now());
                $online = $d->h > 0 ? $d->h.'hr '.$d->i.'min' : $d->i.'min';
            @endphp
            <div class="zh-tr">
                <span class="c1"><b>{{ $controller->callsign }}</b>
                    @if($controller->is_instructing == 1)<em class="zh-badge zh-b-red">Instructing</em>@endif
                    @if($controller->is_student == 1)<em class="zh-badge zh-b-amber">Training</em>@endif
                    @if($controller->is_ctp == 1)<em class="zh-badge zh-b-blue">CTP</em>@endif
                </span>
                <span class="c2">{{ $controller->user ? $controller->user->fullName('FLC') : $controller->cid }}</span>
                <span class="c3">{{ $online }}</span>
            </div>
        @endforeach
    </div>
@endif
