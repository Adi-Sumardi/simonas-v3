@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
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
© {{ date('Y') }} SIMONAS Digital Asrama YAPI. @lang('All rights reserved.')<br>
<span style="color:#cbd5e1;">Email ini dikirim otomatis, mohon tidak membalas ke alamat ini.</span>
@endcomponent
@endslot
@endcomponent
