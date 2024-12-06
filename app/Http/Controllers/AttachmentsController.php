<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function index()
    {
        if (auth()->user()->super_admin) {
            $files = Attachment::orderBy('created_at', 'desc')->paginate(env('PER_PAGE'));
        } else {
            $organizations = Organization::where('admin_id', auth()->id())->pluck('id')->toArray();
            $files = Attachment::whereIn('organization_id', $organizations)->orderBy('created_at', 'desc')->paginate(env('PER_PAGE'));
        }
        return view('attachments.index', ['files' => $files]);
    }

    public function approve($id)
    {
        $file = Attachment::find($id);
        if (!$file) {
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file) {
            if (auth()->user()->super_admin || auth()->user()->isOrganizationAdmin($file->organization_id)) {
                $file->status = Attachment::APPROVED;
                $file->save();
            } else {
                return redirect()->back()->withErrors('You are not authorized to approve this file.');
            }
        }
        return redirect()->back()->with('success', 'File approved successfully.');
    }

    public function block($id)
    {
        $file = Attachment::find($id);
        if (!$file) {
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file) {
            if (auth()->user()->super_admin || auth()->user()->isOrganizationAdmin($file->organization_id)) {
                $file->status = Attachment::BLOCKED;
                $file->save();
            } else {
                return redirect()->back()->withErrors('You are not authorized to block this file.');
            }
        }
        return redirect()->back()->with('success', 'File blocked successfully.');
    }

    public function destroy($id)
    {
        $file = Attachment::find($id);
        if (!$file) {
            return redirect()->back()->withErrors('File not found.');
        }
        if ($file) {
            if (auth()->user()->super_admin || auth()->user()->isOrganizationAdmin($file->organization_id)) {

                if (Storage::disk('public')->exists('attachments/' . $file->file_name)) {
                    Storage::disk('public')->delete('attachments/' . $file->file_name);
                }
                $file->delete();
            }else{
                return redirect()->back()->withErrors('You are not authorized to delete this file.');
            }
        }
        return redirect()->back()->with('success', 'File has been deleted successfully.');
    }
}
