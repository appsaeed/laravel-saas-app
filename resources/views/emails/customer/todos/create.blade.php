@component('mail::message')
    {!! $message !!}
    @component('mail::button', ['url' => $url])
        opne task
    @endcomponent

    {{ __('locale.labels.thanks') }}
    {{ config('app.name') }}
    Visit: {{ url('/') }}
@endcomponent
