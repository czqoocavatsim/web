@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => route('index')])
Gander Oceanic OCA
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<p>To change your subscription options or manage your membership with Gander Oceanic, <a href="https://ganderoceanic.com" rel="noopener" target="_blank">visit our website.</a></p>
<p style="font-weight:600">This email was sent from an unmonitored address. Replies will not be read.</p>
<p>Copyright © {{App\Models\Settings\CoreSettings::where('id', 1)->firstOrFail()->copyright_year}} Gander Oceanic - All Rights Reserved</p>
@endcomponent
@endslot
@endcomponent
