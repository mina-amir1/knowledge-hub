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
                        <h3 class="mb-0">Organizations</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                            <!--begin::Form-->
                            <form action="{{ route('organizations.update',$organization->id) }}" method="POST" enctype="multipart/form-data"> <!--begin::Body-->
                                @csrf
                                <div class="card-body">
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Organisation
                                            Name</label> <input type="text" name="name" required class="form-control"
                                                                value="{{ $organization->name ?? '' }}"
                                                                id="exampleInputPassword1"></div>

                                    <div class="mb-3"><label for="exampleInputEmail1"
                                                             class="form-label">Expertise</label>
                                        <select class="form-control select2" id="expertise-select" name="expertises[]"
                                                multiple="multiple" required>
                                            @php $expertises = $organization->expertises->pluck('id')->toArray(); @endphp
                                            @foreach(\App\Models\Expertise::all() as $expertise)
                                                <option value="{{ $expertise->id }}" {{ in_array($expertise->id,$expertises)? 'selected' : '' }}>
                                                    {{ $expertise->field }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3"><label for="exampleInputEmail1"
                                                             class="form-label">Locations</label>
                                        <select class="form-control select2" id="location-select" name="locations[]"
                                                multiple="multiple" required>
                                        @php $locations = $organization->locations->pluck('id')->toArray(); @endphp
                                        @foreach(\App\Models\Location::all() as $location)
                                                <option value="{{ $location->id }}" {{ in_array($location->id,$locations)? 'selected' : '' }}>
                                                    {{ $location->province }}
                                                   </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3"><label for="exampleInputEmail1"
                                                             class="form-label">Organization Admin</label>
                                        <select class="form-control select2" id="user-select" name="admin_id"
                                                required>
                                            <option hidden></option>
                                            @foreach(\App\Models\User::active()->get() as $user)

                                                <option value="{{ $user->id }}" {{$user->id == $organization->admin_id ? 'selected': ''}}>{{ $user->name }} -- {{ $user->email }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Organisation
                                            About</label><textarea name="about" class="form-control"
                                        id="exampleInputPassword1" max="255">{{ $organization->about }}</textarea></div>

                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">No of
                                            Employees</label> <select name="no_employees" class="form-control">
                                        <option hidden></option>
                                        <option value="1-50" {{ $organization->no_employees === '1-50' ? 'selected' : '' }}>1-50</option>
                                        <option value="51-100" {{ $organization->no_employees === '51-100' ? 'selected' : '' }}>51-100</option>
                                        <option value="101-200" {{ $organization->no_employees === '101-200' ? 'selected' : '' }}>101-200</option>
                                        <option value="201-300" {{ $organization->no_employees === '201-300' ? 'selected' : '' }}>201-300</option>
                                        <option value="301-400" {{ $organization->no_employees === '301-400' ? 'selected' : '' }}>301-400</option>
                                        <option value="401-500" {{ $organization->no_employees === '401-500' ? 'selected' : '' }}>401-500</option>
                                        <option value="500+" {{ $organization->no_employees === '500+' ? 'selected' : '' }}>500+</option>
                                        </select>
                                    </div>

                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Facebook
                                            </label> <input type="text" name="facebook_link" class="form-control"
                                                            value="{{ $organization->facebook_link }}"
                                                                 id="exampleInputPassword1" max="255"></div>
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Instagram
                                        </label> <input type="text" name="instagram_link" class="form-control"
                                                        value="{{ $organization->instagram_link }}"
                                                        id="exampleInputPassword1" max="255"></div>
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Website
                                        </label> <input type="text" name="website_link" class="form-control"
                                                        value="{{ $organization->website_link }}"
                                                        id="exampleInputPassword1" max="255"></div>

                                    <label for="inputGroupFile02">Logo/Image</label>
                                    <div class="input-group mb-3"> <input type="file" name="logo" class="form-control" id="inputGroupFile02"> <label class="input-group-text" for="inputGroupFile02">Upload</label> </div>
{{--                                 <div class="mb-3 form-check"> <input type="checkbox" class="form-check-input" id="exampleCheck1"> <label class="form-check-label" for="exampleCheck1">Check me out</label> </div>--}}

                                    <h2>Contact Person Details: </h2>
                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Contact Person
                                            Name</label> <input type="text" name="contact_person_name" required class="form-control"
                                                                value="{{ $organization->contact_person_name }}"
                                                                id="exampleInputPassword1"></div>

                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Contact Person Phone
                                        </label> <input type="text" name="contact_person_phone" required class="form-control"
                                                        value="{{ $organization->contact_person_phone }}"
                                                        id="exampleInputPassword1"></div>

                                    <div class="mb-3"><label for="exampleInputPassword1" class="form-label">Contact Person
                                            Email</label> <input type="text" name="contact_person_email" required class="form-control"
                                                                value="{{ $organization->contact_person_email }}"
                                                                 id="exampleInputPassword1"></div>

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
            $('#user-select').select2();
        });
    </script>
@endsection
