<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function index()
    {
        $files = Attachment::orderBy('created_at','desc')->paginate(env('PER_PAGE'));
        return view('attachments.index',['files' => $files]);
    }

    public function approve($id)
    {
        $file = Attachment::find($id);
        if (!$file){
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file){
            $file->status = Attachment::APPROVED;
            $file->save();
        }
        return redirect()->back()->with('success','File approved successfully.');
    }

    public function block($id)
    {
        $file = Attachment::find($id);
        if (!$file){
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file){
            $file->status = Attachment::BLOCKED;
            $file->save();
        }
        return redirect()->back()->with('success','File blocked successfully.');
    }

    public function destroy($id)
    {
        $file = Attachment::find($id);
        if (!$file){
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file){
            if (Storage::disk('public')->exists('attachments/' . $file->file_name)) {
                Storage::disk('public')->delete('attachments/' . $file->file_name);
            }
            $file->delete();
        }
        return redirect()->back()->with('success', 'File has been deleted successfully.');
    }
}
