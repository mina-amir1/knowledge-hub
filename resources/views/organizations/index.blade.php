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
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Name</th>
                                        <th>Contact Person</th>
                                        <th>Contact Person email</th>
                                        <th>Contact Person phone</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($organizations as $organization)
                                        <tr class="align-middle">
                                            <td><a href="{{ route('organizations.show',[$organization->id]) }}" class="text-decoration-none">
                                                    {{ $organization->id }}</a>
                                            </td>
                                            <td>{{ $organization->name }}</td>
                                            <td>{{ $organization->contact_person_name }}</td>
                                            <td>{{ $organization->contact_person_email}}</td>
                                            <td>{{ $organization->contact_person_phone }}</td>
                                            <td>
                                                @if($organization->admin_id === Auth::id() || Auth::user()->super_admin )
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
