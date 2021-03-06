<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>tado° Garmin Connect IQ bridge</title>

    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Nunito:200,600&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600&display=swap" media="print" onload="this.media='all'" />
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<div class="container flex-center position-ref">
    @if (! Route::is('home'))
        <div class="top-right links">
            <a href="{{ route('home') }}">Home</a>
        </div>
    @endif

    <div class="content">
        @yield ('content')
    </div>
</div>
</body>
</html>
