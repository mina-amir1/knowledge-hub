<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store($thread_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string'
        ],[
            'body.required' => 'The comment field is required.'
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $comment = Comment::create([
            'body' => $request->get('body'),
            'user_id' => auth()->id(),
            'thread_id' => $thread_id
        ]);
        if ($request->hasFile('files')){
            foreach ($request->file('files') as $file){
                $name = uniqid() .'_' . time() . $file->getClientOriginalName();
                $file->storeAs('attachments', $name, 'public');
                $comment->attachments()->create([
                    'user_id' => auth()->id(),
                    'original_name' => $file->getClientOriginalName(),
                    'file_name' => $name,
                    'thread_id' => $thread_id,
                    'comment_id' => $comment->id,
                    'status' => Attachment::PENDING
                ]);
            }
        }
        return redirect()->back()->with(['focusDiv' => 'editor']);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment){
            return view('error')->with(['no'=>'404','error'=> 'Comment not found.']);
        }
        if ($comment->user_id != auth()->id()){
            return view('error')->with(['no'=>'403','error'=> 'You are not authorized to delete this comment.']);
        }
        $comment->delete();
        return redirect()->back()->with(['focusDiv' => 'editor']);
    }
}
