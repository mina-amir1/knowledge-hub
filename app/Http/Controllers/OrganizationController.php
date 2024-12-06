<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\MeetingCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::paginate(env('PER_PAGE'));
        return view('organizations.index', compact('organizations'));
    }

    public function createOrganization()
    {
        if (!auth()->user()->super_admin) {
            return redirect()->route('organizations.index')->withErrors(['You are not authorized to create an organization.']);
        }
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->super_admin) {
            return redirect()->route('organizations.index')->withErrors(['You are not authorized to create an organization.']);
        }
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Set size limit as needed
            'name' => 'required|string|max:255',
            'about' => 'nullable|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:255',
            'contact_person_email' => 'required|email',
            'no_employees' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:255',
            'instagram_link' => 'nullable|string|max:255',
            'website_link' => 'nullable|string|max:255',
            'admin_id' => 'required|exists:users,id', // Set the admin_id to the current user id
            'expertises' => 'required|array',
            'locations' => 'required|array',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $organization= Organization::create($request->except('logo'));

        if ($organization) {
            $organization->expertises()->sync($request->get('expertises',[]));
            $organization->locations()->sync($request->get('locations',[]));

            if ($request->hasFile('logo')) {
                $filename = Str::uuid()->toString() .'.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->storeAs('logo', $filename, 'public');
                $organization->logo = $filename;
            }

            $organization->save();

            return redirect()->route('organizations.create')->with('success', 'Organization created successfully.');
        }

        return redirect()->route('meetings.create')->with('error', 'Something went wrong.');
    }

    public function show($id)
    {
        $organization = Organization::find($id);
        $organizationUsers = $organization->users()->paginate(env('PER_PAGE'));
        return view('organizations.show', compact('organization','organizationUsers'));
    }

    public function edit($id)
    {
        $organization = Organization::find($id);
        if (!$organization->canAccess(Auth::id())) {
            return redirect()->route('organizations.index')->withErrors( 'You are not authorized to access this organization.');
        }
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);
        if (!$organization->canAccess(Auth::id())) {
            return redirect()->route('meetings.index')->withErrors( 'You are not authorized to access this organization.');
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Set size limit as needed
            'name' => 'required|string|max:255',
            'about' => 'nullable|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_phone' => 'required|string|max:255',
            'contact_person_email' => 'required|email',
            'no_employees' => 'nullable|string',
            'facebook_link' => 'nullable|string|max:255',
            'instagram_link' => 'nullable|string|max:255',
            'website_link' => 'nullable|string|max:255',
            'admin_id' => 'required|exists:users,id', // Set the admin_id to the current user id
            'expertises' => 'required|array',
            'locations' => 'required|array',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $organization->update($request->except('logo'));

        $organization->expertises()->sync($request->get('expertises',[]));
        $organization->locations()->sync($request->get('locations',[]));

        if ($request->hasFile('logo')) {
            // Delete the existing avatar if exists
            if ($organization->logo) {
                Storage::disk('public')->delete("logo/".$organization->logo);
            }

            $filename = Str::uuid()->toString() .'.' . $request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->storeAs('logo', $filename, 'public');
            $organization->logo = $filename;
        }

        $organization->save();


        return redirect()->route('organizations.index', $id)->with('success', 'Organization updated successfully.');
    }
    public function destroy($id)
    {
        $organization = Organization::find($id);
        if (!Auth::user()->super_admin) {
            return redirect()->route('organizations.index')->withErrors( 'You are not authorized to access this organization.');
        }
        $organization->expertises()->delete();
        $organization->locations()->delete();

        $organization->delete();
        return redirect()->route('organizations.index')->with('success', 'Organization deleted successfully.');
    }

}
