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
                        <h3 class="mb-0">Users Contacts</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Users Contacts
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

                            {{-- Email Filter --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                           placeholder="Enter email" value="{{ request('email') }}">
                                </div>
                            </div>

                            {{-- Organization Filter --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="organization">Organization</label>
                                    <input type="text" name="organization" id="organization" class="form-control"
                                           placeholder="Enter organization" value="{{ request('organization') }}">
                                </div>
                            </div>

                            {{-- Position Filter --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Position</label>
                                    <select name="position" id="position" class="form-control">
                                        <option value="">-- All --</option>
                                        <option value="admin" {{ request('position') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('position') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Filter & Reset Buttons --}}
                            <div class="col-md-3 mt-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('contacts') }}" class="btn btn-secondary mx-2">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="col-md-12"><!--begin::Quick Example-->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title mt-2">Users Contacts</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Organisation</th>
                                        <th>Role</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($contacts as $user)
                                        @php
                                        if (!$user->organization_id){
                                            $organization = \App\Models\Organization::where('admin_id',$user->id)->first();
                                            $organizationId = $organization ? $organization->id : '';
                                            $organizationName = $organization ? $organization->name : '';
                                        }else{
                                             $organizationId = $user->organization_id ?? '';
                                             $organizationName = $user->organization?->name;
                                        }
                                        @endphp
                                    <tr class="align-middle">
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td><a href="{{ $organizationId ? route('organizations.show',$organizationId) : '' }}" class="text-decoration-none"> {{ $organizationName }}</a></td>
                                        <td>{{ ucwords($user->getRoleNames()?->first()) }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer ">
                                <div class="d-flex justify-content-end">
                                    {{ $contacts->links() }}
                                </div>
                            </div>
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
