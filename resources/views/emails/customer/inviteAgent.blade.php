@component('mail::message')
<h2>Add new agent</h2>


You Reccived this email for add number to {{$data->voicebox}} vocebox of extension.
if you don't accept this invite this will expired after an hour.

@component('mail::button', ['url' => $url])
Add Number
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
