@component('mail::message')
{!! $content !!}
@component('mail::button', ['url' => route('login')])
{{ __('locale.menu.Dashboard') }}
@endcomponent

{{ __('locale.labels.thanks') }},<br>
{{ config('app.name') }}
@endcomponent
