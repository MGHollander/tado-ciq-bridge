@extends ('layout')

@section ('content')
    <div class="title m-b-md">
        {{ config('app.name') }}
    </div>

    <div class="links">
        <a href="https://github.com/MGHollander/strava-ciq-bridge">GitHub</a>
        <a href="https://www.tado.com/">tado° website</a>
        <a href="https://my.tado.com/">tado° web app</a>
        <a href="https://developer.garmin.com/">Connect IQ developer docs</a>
    </div>
@endsection
