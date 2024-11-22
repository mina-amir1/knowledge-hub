<?php

namespace App\Http\Controllers;

use App\Mail\ActivateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::role('user')->paginate(env('PER_PAGE'));
        return view('users.index', compact('users'));
    }

    public function createUser()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make('password1'),
            'activation_token' => Str::random(60)
        ]);

        if ($user) {
            $user->assignRole('user');
            Mail::to($user->email)->send(new ActivateUser($user));
            return redirect()->route('users.create')->with('success', 'User created successfully.');
        } else {
            return redirect()->route('users.create')->with('error', 'Something went wrong.');
        }
    }

    public function block($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_blocked = true;
            $user->save();
            return redirect()->route('users.index')->with('success', 'User blocked successfully.');
        } else {
            return redirect()->route('users.index')->withErrors(['User not Found!']);
        }
    }

    public function unblock($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_blocked = false;
            $user->save();
            return redirect()->route('users.index')->with('success', 'User unblocked successfully.');
        } else {
            return redirect()->route('users.index')->withErrors(['User not Found!']);
        }
    }

    public function reinvite($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('users.index')->withErrors(['User already accepted the invitation!']);
            }
            $user->activation_token = Str::random(60);
            $user->save();
            Mail::to($user->email)->send(new ActivateUser($user));
            return redirect()->route('users.index')->with('success', 'User has been invited successfully.');
        } else {
            return redirect()->route('users.index')->withErrors(['User not Found!']);
        }
    }

    public function profileShow()
    {
        return view('users.profile');
    }

    public function profileUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Set size limit as needed
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('profile.show')->withErrors(['User not Found!']);
        }

        if ($request->hasFile('avatar')) {
            // Delete the existing avatar if exists
            if ($user->avatar && $user->avatar !== 'avatar5.png') {
                Storage::delete($user->avatar);
            }

            $filename = Str::uuid()->toString() .'.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('img', $filename, 'public');
            $user->avatar = $filename;
        }
        $user->name = $request->get('name');
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function markAsSeen()
    {
        \auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return "success";
    }

}
