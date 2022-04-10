<x-user.layout>

    <div class="container">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 text-center bg-light p-3 mt-5 border">
                <h3>{{ __('Login') }}</h3>
                <form method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="login_input_email" value="{{ old('email') }}">
                        <label for="login_input_email" class="form-label">{{ __('E-mail') }}</label>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="login_input_password" value="">
                        <label for="login_input_password" class="form-label">{{ __('Password') }}</label>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-switch">
                        <input class="form-check-input" type="checkbox" name="remember_me" role="switch" id="remember_me" @if(old('remember_me')) checked="1" @endif>
                        <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('Sign in') }}</button>
                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

</x-user.layout>
