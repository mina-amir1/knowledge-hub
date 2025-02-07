<?php

namespace App\Http\Controllers;

use App\Mail\ActivateUser;
use App\Models\Organization;
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
        if (auth()->user()->super_admin) {
            $users = User::paginate(env('PER_PAGE'));
        } else {
            $organizations = Organization::where('admin_id', auth()->id())->pluck('id')->toArray();
            $users = User::whereIn('organization_id', $organizations)->paginate(env('PER_PAGE'));
        }
        return view('users.index', compact('users'));
    }

    public function createUser()
    {
        if (!auth()->user()->hasRole('admin')|| !auth()->user()->super_admin){
            return \redirect()->back()->withErrors('You are not authorized to create a user.');
        }
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'organization_id' => 'required|exists:organizations,id'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!auth()->user()->super_admin || !auth()->user()->isOrganizationAdmin($request->get('organization_id'))){
            return redirect()->back()->withErrors(['You are not authorized to create a user for this organization.']);
        }

        $is_admin = (bool)$request->get('is_admin');
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'organization_id' => $request->get('organization_id'),
            'password' => Hash::make('password1'),
            'activation_token' => Str::random(60)
        ]);

        if ($user) {
            $is_admin ? $user->assignRole('admin') : $user->assignRole('user');
            if ($is_admin){
                $organization = Organization::find($request->get('organization_id'));
                $organization->admin_id = $user->id;
                $organization->save();
                $user->organization_id = null;
                $user->save();
            }
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
            if (!auth()->user()->super_admin && !auth()->user()->isOrganizationAdmin($user->organization_id)){
                return redirect()->back()->withErrors(['You are not authorized to block a user for this organization.']);
            }
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
            if (!auth()->user()->super_admin && !auth()->user()->isOrganizationAdmin($user->organization_id)){
                return redirect()->back()->withErrors(['You are not authorized to unblock a user for this organization.']);
            }
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
            if (!auth()->user()->super_admin && !auth()->user()->isOrganizationAdmin($user->organization_id)){
                return redirect()->back()->withErrors(['You are not authorized to re-invite a user for this organization.']);
            }
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
            'phone' => 'required|string|max:255',
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
        $user->phone = $request->get('phone');

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function markAsSeen()
    {
        \auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return "success";
    }

    public function contacts(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $organization = $request->get('organization');
        $position = $request->get('position');

        $query = User::active();

        if ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        }
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        if ($organization) {
            $admins = Organization::where('name', 'like', '%' . $organization . '%')->pluck('admin_id');

            $query->where(function ($q) use ($organization, $admins) {
                $q->whereHas('organization', function ($q) use ($organization) {
                    $q->where('name', 'like', '%' . $organization . '%');
                });
                // Include admins of the organization
                $q->orWhereIn('id', $admins);
            });
        }
        if ($position) {
            switch ($position){
                case 'admin':
                    $admins = Organization::pluck('admin_id')->unique();
                    $query->whereIn('id',$admins);
                    $query->role('admin');
                    break;

                case 'user':
                    $query->where('organization_id','!=',null);
                    $query->role('user');
                    break;
            }
        }
        $contacts = $query->paginate(env('PER_PAGE'));
        return view('users.contacts', compact('contacts'));
    }

}
