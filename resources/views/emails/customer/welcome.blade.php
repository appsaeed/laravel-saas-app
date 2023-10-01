@component('mail::message')
{!! $content !!}
@component('mail::button', ['url' => $url])
    {{ __('locale.auth.login') }}
@endcomponent

{{ __('locale.labels.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
