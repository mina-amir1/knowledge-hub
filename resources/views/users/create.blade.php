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
                        <h3 class="mb-0">Add a new User</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Users
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
                            <form action="{{ route('users.store') }}" method="POST"> <!--begin::Body-->
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3"> <label for="exampleInputPassword1" class="form-label">Full Name</label> <input type="text" name="name" required class="form-control" id="exampleInputPassword1"> </div>
                                    <div class="mb-3"> <label for="exampleInputEmail1" class="form-label">Email address</label> <input type="email" name="email" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                        <div id="emailHelp" class="form-text">
                                            We'll invite user with this email.
                                        </div>
                                    </div>
{{--                                    <div class="mb-3"> <label for="exampleInputPassword1" class="form-label">Password</label> <input type="password" class="form-control" id="exampleInputPassword1"> </div>--}}
{{--                                    <label for="exampleInputPassword1" class="form-label">Image</label>--}}
{{--                                    <div class="input-group mb-3"> <input type="file" class="form-control" id="inputGroupFile02"> <label class="input-group-text" for="inputGroupFile02">Upload</label> </div>--}}
{{--                                    <div class="mb-3 form-check"> <input type="checkbox" class="form-check-input" id="exampleCheck1"> <label class="form-check-label" for="exampleCheck1">Check me out</label> </div>--}}
                                </div> <!--end::Body--> <!--begin::Footer-->
                                <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div> <!--end::Footer-->
                            </form> <!--end::Form-->
                        </div> <!--end::Quick Example-->
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
