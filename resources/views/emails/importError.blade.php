@component('mail::message')

<p>An Error Occurred While Uploading Autotrader CSV File.</p>
<strong>Error Message</strong>
<br>
{{$errorMessage}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent