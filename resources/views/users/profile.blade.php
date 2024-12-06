@php use Illuminate\Support\Facades\Auth; @endphp
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
                        <h3 class="mb-0">Profile</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Profile
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
                            <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Full
                                            Name</label> <input type="text" name="name" required class="form-control"
                                                                value="{{ Auth::user()->name ?? '' }}"
                                                                id="exampleInputPassword1"></div>

                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Phone
                                            </label> <input type="text" name="phone" required class="form-control"
                                                                value="{{ Auth::user()->phone ?? '' }}"
                                                                id="exampleInputPassword1"></div>

                                    <label for="inputGroupFile02">Logo/Image</label>
                                    <div class="input-group mb-3"> <input type="file" name="avatar" class="form-control" id="inputGroupFile02"> <label class="input-group-text" for="inputGroupFile02">Upload</label> </div>
{{--                                 <div class="mb-3 form-check"> <input type="checkbox" class="form-check-input" id="exampleCheck1"> <label class="form-check-label" for="exampleCheck1">Check me out</label> </div>--}}
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
        $(document).ready(function (){
            $('#expertise-select').select2();
            $('#location-select').select2();
        });
    </script>
@endsection
