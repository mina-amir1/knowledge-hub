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
                        <h3 class="mb-0">Home</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">KH</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Home
                            </li>
                        </ol>
                    </div>
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content Header--> <!--begin::App Content-->
        @php
        $threads = \App\Models\Thread::orderBy('created_at', 'desc')->take(5)->get();
        @endphp
        <div class="app-content"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Row-->
                <div class="row g-4"> <!--begin::Col-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                                <div class="card-body">
                                    <div class="d-flex justify-content-center mb-3">
                                        <img class="img-fluid"
                                             src="{{ asset('img') }}/logo.png"
                                             alt="Organization Logo"
                                             style="max-width: 250px;">
                                    </div>
                                    <div class="d-flex justify-content-center mb-3">
                                    <h2>Welcome {{auth()->user()->name}}!</h2>
                                    </div>
                                </div> <!--end::Body--> <!--begin::Footer-->
                        </div> <!--end::Quick Example-->
                        <h4>Latest threads</h4>
                        @foreach($threads as $thread)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <a href="{{ route('threads.show',$thread->id) }}" ><h3 class="card-title" style="font-weight: bolder;font-size: larger">{{ $thread->title }}</h3></a>
                                </div> <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <div class="card-body">
                                        <p> {{ Str::limit(str_replace('&nbsp;', ' ', strip_tags($thread->body)), 100, '...') }}</p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>{{ $thread->user->name }} - {{ $thread->created_at->toFormattedDateString() }}</p>
                                </div>
                                <!-- /.card-body -->
                            </div> <!--end::Quick Example-->
                        @endforeach
                        <a class="mx-2 text-decoration-none link" href="{{ route("threads.index") }}">View more...</a>
                    </div> <!--end::Col-->
                </div> <!--end::Row-->

            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
