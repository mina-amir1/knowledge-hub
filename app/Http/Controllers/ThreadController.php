<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $threads = Thread::where('title', 'like', '%' . $request->get('search') . '%')->orWhere('body', 'like', '%' . $request->get('search') . '%')->orderBy('created_at','desc')->paginate(env('PER_PAGE'));
            return view('threads.index', compact('threads'));
        }
        $threads = Thread::orderBy('created_at','desc')->paginate(env('PER_PAGE'));
        return view('threads.index', compact('threads'));
    }

    public function show($id)
    {
        $thread = Thread::find($id);
        if (!$thread){
            return view('error')->with(['no'=>'404','error'=> 'Thread not found.']);
        }
        return view('threads.show', compact('thread'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $organizationID = auth()->user()->organization_id ?? Organization::where('admin_id',auth()->id())->first()->id ?? null;
        $thread = Thread::create([
            'title' => $request->get('title'),
            'body' => $request->input('body'),
            'user_id' => auth()->id(),
            'organization_id' => $organizationID
        ]);
        if ($thread) {
            return redirect()->route('threads.create')->with('success', 'Thread created successfully.');
        }

        return redirect()->route('threads.create')->with('error', 'Something went wrong.');
    }

    public function edit($id)
    {
        $thread = Thread::find($id);
        if (!$thread){
            return view('error')->with(['no'=>'404','error'=> 'Thread not found.']);
        }
        if ($thread->user_id != auth()->id()){
            return view('error')->with(['no'=>'403','error'=> 'You are not authorized to delete this thread.']);
        }
        return view('threads.edit', compact('thread'));
    }

    public function update($id)
    {
        $thread = Thread::find($id);
        if (!$thread){
            return view('error')->with(['no'=>'404','error'=> 'Thread not found.']);
        }
        if ($thread->user_id != auth()->id()){
            return view('error')->with(['no'=>'403','error'=> 'You are not authorized to delete this thread.']);
        }
        $thread->update([
            'title' => request('title'),
            'body' => request('body')
        ]);
        return redirect()->route('threads.index')->with('success', 'Thread updated successfully.');
    }

    public function destroy($id)
    {
        $thread = Thread::find($id);
        if (!$thread){
            return view('error')->with(['no'=>'404','error'=> 'Thread not found.']);
        }
        if ($thread->user_id != auth()->id()){
            return view('error')->with(['no'=>'403','error'=> 'You are not authorized to delete this thread.']);
        }
        $thread->delete();
        return redirect()->route('threads.index')->with('success', 'Thread deleted successfully.');
    }
}
