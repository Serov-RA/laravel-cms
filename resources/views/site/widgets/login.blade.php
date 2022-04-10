@auth

    @if (\Illuminate\Support\Facades\Auth::user()->role->is_admin)
        <a href="{{ route('admin', ['section' => 'site', 'model' => 'page']) }}" class="btn btn-warning">
            {{ __('Manage panel') }}
        </a>
    @endif

    <form method="post" action="{{ route('logout') }}" style="display: inline">
        @csrf
        <button type="submit" class="btn btn-outline-light me-2">{{ __('Sign out') }}</button>
    </form>
@endauth
@guest
    <a href="{{ route('login') }}" class="btn btn-warning">
        {{ __('Sign in') }}
    </a>
@endguest
