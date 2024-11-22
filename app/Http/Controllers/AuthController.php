<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $remember = $request->get('remember',false);

        if (Auth::attempt($credentials, $remember)) {
            // Authentication passed
           return redirect()->route('dashboard');
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
    public function activateUser(Request $request,$token)
    {
        $user = User::where('activation_token',$token)->first();
        if ($user){
            $validator = Validator::make($request->all(),[
                'password' => 'required|string|confirmed|min:8',
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator);
            }
           $user->password = Hash::make($request->get('password'));
           $user->email_verified_at = Carbon::now();
           $user->save();

           Auth::login($user);
           return redirect()->route('dashboard');
        }
        return view('error')->with('error','Activation Failed, We cannot find your account!');
    }
    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
