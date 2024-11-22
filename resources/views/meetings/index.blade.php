@php use Illuminate\Support\Facades\Auth;use Carbon\Carbon; @endphp
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
                        <h3 class="mb-0">Meetings</h3>
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
                    <div class="col-md-12"><!--begin::Quick Example-->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Meetings</h3>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('meetings.create') }}" class="btn btn-outline-primary ms-auto" role="button">Create Meeting</a>
                                </div>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Title</th>
                                        <th>URL</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Admin</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr class="align-middle">
                                            <td><a href="{{ route('meetings.show',[$meeting->id]) }}" class="text-decoration-none">
                                                    {{ $meeting->id }}</a>
                                            </td>
                                            <td>{{ $meeting->title }}</td>
                                            <td><a href="{{ $meeting->url }}"
                                                   target="_blank"
                                                   class="text-decoration-none">{{ $meeting->url }}</a></td>
                                            <td>{{ Carbon::parse($meeting->start_time)->format('d/m/y H:i') }}</td>
                                            <td>{{ Carbon::parse($meeting->end_time)->format('d/m/y H:i') }}</td>
                                            <td>{{ $meeting->user->name }}</td>
                                            <td>
                                                @if($meeting->user_id === Auth::id())
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                   href="{{ route('meetings.edit',[$meeting->id]) }}">Edit</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                               href="{{ route('meetings.destroy',[$meeting->id]) }}">Delete</a>
                                                            </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <div class="d-flex justify-content-end">
                                    {{ $meetings->links() }}
                                </div>
                            </div>
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
