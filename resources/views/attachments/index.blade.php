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
                        <h3 class="mb-0">Attachments</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Attachments
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
                                <h3 class="card-title">Attachments</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Name</th>
                                        <th>Thread</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th style="width: 40px">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($files as $file)
                                    <tr class="align-middle">
                                        <td>{{ $file->id }}</td>
                                        <td><a href="{{ Storage::disk('public')->url('attachments/').$file->file_name }}"
                                               target="_blank"
                                               class="text-decoration-none"
                                               download="{{ $file->original_name }}">
                                                {{ $file->original_name }}
                                            </a></td>
                                        <td><a href="{{ route('threads.show',[$file->thread->id]) }}" target="_blank" class="text-decoration-none">{{ $file->thread->title }}</a></td>
                                        <td>{{ $file->user->name }}</td>
                                        @switch($file->status)
                                            @case(0)
                                                <td><span class="badge bg-warning">Pending</span></td>
                                                @break
                                            @case(1)
                                                <td><span class="badge bg-success">Approved</span></td>
                                                @break
                                            @case(2)
                                                <td><span class="badge bg-danger">Blocked</span></td>
                                                @break
                                        @endswitch
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('attachments.approve',$file->id) }}">Approve</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('attachments.block',$file->id) }}">Block</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('attachments.delete',$file->id) }}">Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <div class="d-flex justify-content-end">
                                    {{ $files->links() }}
                                </div>
                            </div>
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
