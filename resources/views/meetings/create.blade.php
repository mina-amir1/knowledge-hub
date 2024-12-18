@php use App\Models\User; @endphp
@extends('layout')

@section('main')
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
                        <h3 class="mb-0">Add a new Meeting</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Meetings
                            </li>
                        </ol>
                    </div>
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content Header--> <!--begin::App Content-->
        <div class="app-content"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Row-->
                <div class="row g-4"> <!--begin::Col-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                            <!--begin::Form-->
                            <form action="{{ route('meetings.store') }}" method="POST"> <!--begin::Body-->
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3"><label for="exampleInputPassword1"
                                                             class="form-label">Title</label> <input type="text"
                                                                                                     name="title"
                                                                                                     minlength="5"
                                                                                                     required
                                                                                                     class="form-control"
                                                                                                     id="title">
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputPassword1" class="form-label">URL</label>
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="url"  required class="form-control me-2 w-75" id="url-input">
                                            <a id="make-url" class="btn disabled btn-primary">Generate link</a>
                                        </div>
                                    </div>
                                    <div class="mb-3"><label for="exampleInputEmail1" class="form-label">Start Time
                                            (UTC)</label> <input type="datetime-local"
                                                                 min="{{ \Carbon\Carbon::now()->floorMinute() }}"
                                                                 name="start_time" required class="form-control"
                                                                 id="exampleInputEmail1"></div>
                                    <div class="mb-3"><label for="exampleInputEmail1" class="form-label">End Time
                                            (UTC)</label> <input type="datetime-local"
                                                                 min="{{ \Carbon\Carbon::now()->floorMinute() }}"
                                                                 name="end_time" required class="form-control"
                                                                 id="exampleInputEmail1"></div>
                                    <div class="mb-3"><label for="exampleInputEmail1"
                                                             class="form-label">Attendees</label>
                                        <select class="form-control select2" id="example-select" name="attendees[]"
                                                multiple="multiple" required>
                                            @foreach(User::active()->where('id', '!=', Auth::id())->get() as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    - {{ $user->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3"><label for="exampleInputEmail1"
                                                             class="form-label">Description</label>
                                        <textarea name="description" class="form-control"
                                                  id="exampleInputEmail1"></textarea>
                                    </div>
                                </div> <!--end::Body--> <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div> <!--end::Footer-->
                            </form> <!--end::Form-->
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
    <script>
        $(document).ready(function () {
            function generateRandomString() {
                const characters = "abcdefghijklmnopqrstuvwxyz"; // Characters to use
                const segmentLength = 3; // Length of each segment
                const numSegments = 3; // Number of segments
                let result = [];

                for (let i = 0; i < numSegments; i++) {
                    let segment = '';
                    for (let j = 0; j < segmentLength; j++) {
                        segment += characters.charAt(Math.floor(Math.random() * characters.length));
                    }
                    result.push(segment);
                }

                return result.join('-'); // Join segments with dashes
            }

            $('.select2').select2();
            $("#title").on('input', function () {
                const title = $(this).val();
                if ($.trim(title).length > 0) {
                    $("#make-url").removeClass('disabled');
                } else {
                    $("#make-url").addClass('disabled');
                }
            });
            $("#make-url").click(function (event) {
                let meetingTitle = $("#title").val().trim().replace(/\s+/g, '-').toLowerCase();
                $("#url-input").val('https://meet.jit.si/' + meetingTitle);
            });
        });
    </script>
@endsection
