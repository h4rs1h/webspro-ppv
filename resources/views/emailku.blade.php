@component('mail::message')
# Introduction

Saya sedang mengirimkan email dengan laravel.

@component('mail::button', ['url' => $data['url']])
Visit
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
