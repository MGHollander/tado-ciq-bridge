@extends ('layout')

@section ('content')
    <div class="title">
        tado° login
    </div>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-field">
                <label>Email:</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </div>

            @error('email')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-field">
                <label>Password:</label>
                <input type="password" name="password">
            </div>

            @error('password')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-actions">
                <button type="submit">Login</button>
                <a href="https://my.tado.com/webapp/#/account/reset-password">Forgot your password?</a>
            </div>
        </div>
    </form>

    <p>Disclaimer: Your login data will <strong>not</strong> be saved. It is only used to get an access token from tado°.</p>
@endsection
