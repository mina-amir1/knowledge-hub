<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MeetingsController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $meetings = Meeting::paginate(env('PER_PAGE'));
            return view('meetings.index', compact('meetings'));
        }
        $meetings = Meeting::forCurrentUser()->paginate(env('PER_PAGE'));
        return view('meetings.index', compact('meetings'));
    }

    public function createMeeting()
    {
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'start_time' => 'required|date|after_or_equal:' . Carbon::now()->startOfMinute()->toDateTimeString(),
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'attendees' => 'required|array'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meeting = Meeting::create([
            'title' => $request->get('title'),
            'url' => $request->get('url'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'description' => $request->get('description'),
            'user_id' => Auth::id()
        ]);

        if ($meeting) {
            $meeting->attendees()->createMany(
                collect($request->get('attendees',[]))->map(function ($userId) {
                    return ['user_id' => $userId];
                })->toArray()
            );
            $attendees =  User::find($request->get('attendees',[]));
            foreach ($attendees as $attendee) {
                $attendee->notify(new MeetingCreated($meeting,$attendee)); // Send the notification
            }
            return redirect()->route('meetings.create')->with('success', 'Meeting created successfully.');
        } else {
            return redirect()->route('meetings.create')->with('error', 'Something went wrong.');
        }
    }

    public function show($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting->canAccess(Auth::id())) {
            return redirect()->route('meetings.index')->withErrors(['You are not authorized to access this meeting.']);
        }
        return view('meetings.show', compact('meeting'));
    }

    public function edit($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting->canAccess(Auth::id())) {
            return redirect()->route('meetings.index')->withErrors( 'You are not authorized to access this meeting.');
        }
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, $id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting->canAccess(Auth::id())) {
            return redirect()->route('meetings.index')->withErrors( 'You are not authorized to access this meeting.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'start_time' => 'required|date|after_or_equal:' . Carbon::parse($meeting->start_time)->toDateTimeString(),
            'end_time' => 'required|date|after:start_time',
            'description' => 'nullable|string',
            'attendees' => 'required|array'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meeting->title = $request->get('title');
        $meeting->start_time = $request->get('start_time');
        $meeting->end_time = $request->get('end_time');
        $meeting->url = $request->get('url');
        $meeting->description = $request->get('description');
        $meeting->attendees()->delete();
        $meeting->attendees()->createMany(
            collect($request->get('attendees',[]))->map(function ($userId) {
                return ['user_id' => $userId];
            })->toArray()
        );
        $meeting->save();
        $attendees =  User::find($request->get('attendees',[]));
        foreach ($attendees as $attendee) {
            $attendee->notify(new MeetingCreated($meeting,$attendee)); // Send the notification
        }

        return redirect()->route('meetings.edit', $id)->with('success', 'Meeting updated successfully.');
    }
    public function destroy($id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting->user_id == Auth::id()) {
            return redirect()->route('meetings.index')->withErrors( 'You are not authorized to access this meeting.');
        }
        $meeting->attendees()->delete();
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully.');
    }

    public function generateCalendar($user_id, $id)
    {
        $meeting = Meeting::find($id);
        if (!$meeting){
            return view('error')->with(['error'=> 'Meeting not found.']);
        }
        if (!$meeting->canAccess($user_id)) {
            return view('error')->with(['error'=> 'You are not authorized to access this meeting.','no'=>403]);
        }
        $uid = 'meeting-' . $id . '@kh';
        $dtstamp = now()->format('Ymd\THis\Z'); // Current timestamp in UTC
        $startTime = Carbon::parse($meeting->start_time)->format('Ymd\THis\Z') ; // Start time in UTC
        $endTime = Carbon::parse($meeting->end_time)->format('Ymd\THis\Z'); // End time in UTC

        $icsContent = "BEGIN:VCALENDAR\r\n
                        VERSION:2.0\r\n
                        CALSCALE:GREGORIAN\r\n
                        BEGIN:VEVENT\r\n
                        UID:{$uid}\r\n
                        DTSTAMP:{$dtstamp}\r\n
                        DTSTART:{$startTime}\r\n
                        DTEND:{$endTime}\r\n
                        SUMMARY:{$meeting->title}\r\n
                        DESCRIPTION:{$meeting->description}\r\n
                        URL:{$meeting->url}\r\n
                        LAST-MODIFIED:{$dtstamp}\r\n
                        END:VEVENT\r\n
                        END:VCALENDAR";

        $filename = 'meeting-' . $id . '.ics';

        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

}
