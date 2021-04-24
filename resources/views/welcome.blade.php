@extends ('layout')

@section ('content')
    <h1 class="title">
        {{ config('app.name') }}
    </h1>

    <aside class="links">
        <a href="https://github.com/MGHollander/tado-ciq-bridge">GitHub</a>
        <a href="https://www.tado.com/">tado° website</a>
        <a href="https://my.tado.com/">tado° web app</a>
        <a href="https://developer.garmin.com/connect-iq/overview/">Connect IQ developer docs</a>
    </aside>
@endsection
