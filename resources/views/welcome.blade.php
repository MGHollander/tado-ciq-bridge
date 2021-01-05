@extends ('layout')

@section ('content')
    <div class="title">
        {{ config('app.name') }}
    </div>

    <div class="links">
        <a href="https://github.com/MGHollander/tado-ciq-bridge">GitHub</a>
        <a href="https://www.tado.com/">tado° website</a>
        <a href="https://my.tado.com/">tado° web app</a>
        <a href="https://developer.garmin.com/connect-iq/overview/">Connect IQ developer docs</a>
    </div>
@endsection
