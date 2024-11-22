@php use App\Models\Attachment;use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Storage; @endphp
@extends('layout')

@section('main')
    <style>
        .cke_notifications_area {
            display: none !important;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
    <main class="app-main"> <!--begin::App Content Header-->
        <div class="app-content-header"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Row-->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Thread</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Threads
                            </li>
                        </ol>
                    </div>
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content Header--> <!--begin::App Content-->
        <div class="app-content"> <!--begin::Container-->
            <div class="container-fluid"> <!-- Timelime example  -->
                <div class="row">
                    <div class="col-md-12"> <!-- The time line -->
                        <div class="timeline"> <!-- timeline time label -->
                            <div class="time-label"><span
                                    class="text-bg-success">{{ $thread->created_at->toFormattedDateString() }}</span>
                            </div> <!-- /.timeline-label --> <!-- timeline item -->
                            <div><i class="timeline-icon bi bi-file-text-fill text-bg-primary"></i>
                                <div class="timeline-item"> <span class="time"> <i class="bi bi-clock-fill"></i> {{ $thread->created_at->format('H:i') }}
                                        </span>
                                    <h3 class="timeline-header">
                                        <a href="#">{{$thread->title}} </a>
                                        - {{ $thread->user->name }}
                                    </h3>
                                    <div class="timeline-body">
                                        {!! $thread->body !!}
                                    </div>
                                    <div class="timeline-footer">
                                        <a href="javascript:void(0);" onclick="scrolldiv()"
                                           class="btn btn-outline-dark btn-sm">Comment</a>
                                        @if($thread->user_id === Auth::user()->id)
                                            <a href="{{ route('threads.edit',$thread->id ) }}"
                                               class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ route('threads.delete',$thread->id ) }}"
                                               class="btn btn-danger btn-sm">Delete</a>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- END timeline item --> <!-- timeline item -->
                            <hr class="my-4">
                            @foreach($thread->comments as $comment)
                                <div><i class="timeline-icon bi bi-chat-text-fill text-bg-warning"></i>
                                    <div class="timeline-item"> <span class="time"> <i class="bi bi-clock-fill"></i> {{ $comment->created_at->format('d M Y H:i') }}
                                        </span>
                                        <h3 class="timeline-header"><a href="#">{{ $comment->user->name }}</a> commented
                                            on the thread
                                        </h3>
                                        <div class="timeline-body">
                                            {!! $comment->body !!}
                                        </div>
                                        @if($comment->attachments->count() > 0 && $comment->hasApprovedFiles())
                                            <div class="timeline-footer">
                                                <hr>
                                                <h4>Attachments</h4>
                                                <div class="row">
                                                    @foreach($comment->attachments as $file)
                                                        @if($file->status === Attachment::APPROVED)
                                                            <li>
                                                                <a href="{{ Storage::disk('public')->url('attachments/').$file->file_name }}"
                                                                   target="_blank"
                                                                   download="{{ $file->original_name }}">
                                                                    {{ $file->original_name }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if($comment->user_id === Auth::user()->id)
                                            <div class="timeline-footer"><a
                                                    href="{{ route('comment.destroy', [$comment->id]) }}"
                                                    class="btn btn-danger btn-sm">Delete
                                                    comment</a>
                                            </div>
                                        @endif
                                    </div>
                                </div> <!-- END timeline item --> <!-- timeline time label -->
                            @endforeach
                            <div><i class="timeline-icon bi bi-clock-fill text-bg-secondary"></i></div>
                            <div class="card card-primary mt-5 mb-4"> <!--begin::Header-->
                                <!--begin::Form-->
                                <form action="{{ route('comment.store',[$thread->id]) }}" method="POST"
                                      enctype="multipart/form-data"> <!--begin::Body-->
                                    @csrf
                                    <div class="card-body">
                                        <div class="mb-3"><label for="exampleInputEmail1" class="form-label">Place a
                                                Comment</label>
                                            <textarea name="body" id="editor"></textarea>
                                        </div>
                                        <label for="inputGroupFile02">Attachments</label>
                                        <div class="input-group mb-3"><input type="file" name="files[]" multiple
                                                                             class="form-control" id="inputGroupFile02">
                                            <label class="input-group-text" for="inputGroupFile02">Upload</label></div>
                                    </div><!--end::Body--> <!--begin::Footer-->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div> <!--end::Footer-->
                                </form> <!--end::Form-->
                            </div> <!--end::Quick Example-->
                        </div>
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('editor');

        function focusEditor() {
            document.getElementById('cke_editor').focus();
            document.getElementById('cke_editor').select();
        }

        function scrolldiv() {
            var elem = document.getElementById("cke_editor");
            elem.scrollIntoView();
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Check if session contains the 'focusDiv' variable
            @if(session('focusDiv'))
            let targetDiv = document.getElementById("{{ session('focusDiv') }}");
            if (targetDiv) {
                targetDiv.focus();
                targetDiv.scrollIntoView({behavior: 'smooth', block: 'center'});
            }
            @endif
        });
    </script>
@endsection
