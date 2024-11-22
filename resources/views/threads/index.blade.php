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
                        <h3 class="mb-0">Threads</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Threads
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
                        <div class="container my-3">
                            <form action="" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Search..." aria-label="Search">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    @foreach($threads as $thread)
                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="{{ route('threads.show',$thread->id) }}" ><h3 class="card-title" style="font-weight: bolder;font-size: larger">{{ $thread->title }}</h3></a>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="card-body">
                                    <p> {{ Str::limit(strip_tags($thread->body), 100, '...') }}</p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>{{ $thread->user->name }} - {{ $thread->created_at->toFormattedDateString() }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div> <!--end::Quick Example-->
                        @endforeach
                        {{ $threads->links() }}
                    </div> <!--end::Col-->
                </div> <!--end::Row-->
            </div> <!--end::Container-->
        </div> <!--end::App Content-->
    </main>
@endsection
