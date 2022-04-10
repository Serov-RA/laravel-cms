<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request): Factory|View|RedirectResponse|Application
    {
        $credentials = $request->only('email', 'password');

        if ($request->isMethod('post')) {
            if (Auth::attempt($credentials, $request->boolean('remember_me'))) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }

            return back()
                ->withErrors(['email' => __('Incorrect email or password')])
                ->withInput($request->except('password'));
        }

        return view('user.login');
    }

    public function logout(Request $request): Redirector|Application|RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
