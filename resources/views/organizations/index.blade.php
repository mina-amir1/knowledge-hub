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
                        <h3 class="mb-0">Organizations</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Organizations
                            </li>
                        </ol>
                    </div>
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content Header--> <!--begin::App Content-->
        <div class="app-content"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Row-->

                <div class="row g-4"> <!--begin::Col-->
                    <form method="GET" action="" class="mb-3">
                        <div class="row">
                            {{-- Name Filter --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Enter name" value="{{ request('name') }}">
                                </div>
                            </div>
                            {{-- Filter & Reset Buttons --}}
                            <div class="col-md-3 mt-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('organizations.index') }}" class="btn btn-secondary mx-2">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12"><!--begin::Quick Example-->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Organizations</h3>
                                @if(\auth()->user()->super_admin)
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('organizations.create') }}" class="btn btn-outline-primary ms-auto" role="button">Create Organization</a>
                                </div>
                                @endif
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                @php
                                $is_admin = false;
                                foreach ($organizations as $organization) {
                                    if ($organization->admin_id === Auth::id()) {
                                        $is_admin = true;
                                        break;
                                    }
                                }
                                @endphp
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        @if($is_admin || Auth::user()->super_admin )
                                        <th>Actions</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($organizations as $organization)
                                        <tr class="align-middle">
                                            <td>{{ $organization->id }}</td>
                                            <td> <a href="{{ route('organizations.show',[$organization->id]) }}" class="text-decoration-none">
                                                    {{ $organization->name }}</a>
                                            </td>
                                            <td><img class="rounded-circle img-fluid"
                                                     src="{{ $organization->logo ? Storage::disk('public')->url('logo/'.$organization->logo) : asset('img/orgAvatar.png') }}"
                                                     alt="Organization Logo"
                                                     style="max-width: 50px;">
                                            </td>
                                            @if($organization->admin_id === Auth::id() || Auth::user()->super_admin )
                                            <td>
                                                  <div class="dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                   href="{{ route('organizations.edit',[$organization->id]) }}">Edit</a>
                                                            </li>
                                                        @if(Auth::user()->super_admin)
                                                            <li><a class="dropdown-item"
                                                               href="{{ route('organizations.destroy',[$organization->id]) }}">Delete</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                  </div>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <div class="d-flex justify-content-end">
                                    {{ $organizations->links() }}
                                </div>
                            </div>
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
