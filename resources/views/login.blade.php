@extends ('layout')

@section ('content')
    <div class="title m-b-md">
        {{ config('app.name') }} login
    </div>

    <form method="POST" action="">
        @csrf

        <p>
            <label>Username:</label>
            <input type="text" name="username">
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="password">
        </p>
    </form>
@endsection
