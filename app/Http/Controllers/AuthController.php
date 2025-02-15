<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm() {
        if (Auth::check()){
            return redirect()->route('admin');
        }
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        $remember = $request->get('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed
           return redirect()->route('home');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function activationShow($token)
    {
        $user = User::where('activation_token',$token)->first();
        if ($user && !$user->email_verified_at){
            return view('auth.activation',compact('user'));
        }
        if ($user && $user->email_verified_at){
            return view('error')->with(['no'=>'422','error'=>'Account already activated!']);
        }
        return view('error')->with('error','We cannot find your account!');
    }

    public function activateUser(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->first();
        if ($user) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|confirmed|min:8',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $user->password = Hash::make($request->get('password'));
            $user->email_verified_at = Carbon::now();
            $user->save();

            Auth::login($user);
            return redirect()->route('home');
        }
        return view('error')->with('error', 'Activation Failed, We cannot find your account!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgetPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'));

        return $response === Password::RESET_LINK_SENT
            ? back()->with('success', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required',
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        // Attempt to reset the password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );
        // Check the response and redirect accordingly
        if ($response === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', trans($response));
        }

        // If the password reset fails, throw a validation exception
        throw ValidationException::withMessages(['email' => trans($response)]);
    }
}
