@php
    use App\Models\User;use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use App\Models\ChMessage as Message;
    use Illuminate\Support\Facades\Storage;
      $unreadMessages = Message::where('to_id', Auth::id())->where('seen', 0)->limit(20)->orderBy('created_at','desc')->get();
      $notifications = auth()->user()->notifications;
@endphp
<nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i
                        class="bi bi-list"></i> </a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Dashboard</a></li>
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown"><a class="nav-link" data-bs-toggle="dropdown" href="#"> <i
                        class="bi bi-chat-text"></i>
                    @if($unreadMessages->count() > 0 )
                        <span class="navbar-badge badge text-bg-danger">{{ $unreadMessages->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    @foreach($unreadMessages as $message)
                        <a href="{{ route('user', User::find($message->from_id) ? User::find($message->from_id)->id : '' ) }}" class="dropdown-item"> <!--begin::Message-->
                            <div class="d-flex">
                                <div class="flex-shrink-0"><img
                                        src="{{ User::find($message->from_id) ? Storage::url('img/'.User::find($message->from_id)->avatar) : asset('img/avatar5.png') }}"
                                        alt="User Avatar"
                                        class="img-size-50 rounded-circle me-3"></div>
                                <div class="flex-grow-1">
                                    <h3 class="dropdown-item-title">
                                        {{ User::find($message->from_id) ? User::find($message->from_id)->name : 'Unknown' }}
                                    </h3>
                                    <p class="fs-7">{{ $message->body }}</p>
                                    <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> {{ Carbon::parse($message->created_at)->diffForHumans()}}
                                    </p>
                                </div>
                            </div> <!--end::Message-->
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                    <a href="{{ route(config('chatify.routes.prefix')) }}" class="dropdown-item dropdown-footer">See All
                        Messages</a>
                </div>
            </li> <!--end::Messages Dropdown Menu-->
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown"><a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    @if(\auth()->user()->unreadNotifications && \auth()->user()->unreadNotifications->count() > 0)
                    <span class="navbar-badge badge text-bg-warning">{{ \auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"><span
                        class="dropdown-item dropdown-header">{{ \auth()->user()->unreadNotifications->count() ?? '0' }} New Notifications</span>
                    <div class="dropdown-divider"></div>
                    @if($notifications)
                    @foreach($notifications as $notification)
                    <a href="{{ route('meetings.show',$notification->data['meeting_id']) }}" class="dropdown-item"> <i class="bi bi-people-fill me-2 fw-bolder"> Meeting Invitation!</i>
                        <span class="float-end text-secondary fs-7">12 hours</span>
                        <p class="ms-4">{{ $notification->data['meeting_title'] }}</p>
                        <p class="ms-4">{{ Carbon::parse($notification->data['start_time'])->format('d/m/y H:i') }}</p>
                    </a>
                    <div class="dropdown-divider"></div>
                    @endforeach
                    @endif
{{--                    <a href="#" class="dropdown-item dropdown-footer">--}}
{{--                        See All Notifications--}}
{{--                    </a>--}}
                </div>
            </li> <!--end::Notifications Dropdown Menu--> <!--begin::Fullscreen Toggle-->
            <li class="nav-item"><a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize"
                                                                                               class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a></li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu"><a href="#" class="nav-link dropdown-toggle"
                                                       data-bs-toggle="dropdown"> <img
                        src="{{ Auth::user()->avatar ? Storage::url('img/'. Auth::user()->avatar) : asset('img/avatar5.png') }}"
                        class="user-image rounded-circle shadow"
                        alt="User Image"> <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                    <li class="user-header text-bg-primary"><img
                            src="{{ Auth::user()->avatar ? Storage::url('img/'. Auth::user()->avatar) : asset('img/avatar5.png') }}"
                            class="rounded-circle shadow" alt="User Image">
                        <p>
                            {{ Auth::user()->name}}
                            <small>Member since {{ Carbon::parse(Auth::user()->verified_at)->format('M. Y') }}</small>
                        </p>
                    </li> <!--end::User Image--> <!--begin::Menu Body-->
                    {{--                    <li class="user-body"> <!--begin::Row-->--}}
                    {{--                        <div class="row">--}}
                    {{--                            <div class="col-4 text-center"><a href="#">Followers</a></div>--}}
                    {{--                            <div class="col-4 text-center"><a href="#">Sales</a></div>--}}
                    {{--                            <div class="col-4 text-center"><a href="#">Friends</a></div>--}}
                    {{--                        </div> <!--end::Row-->--}}
                    {{--                    </li> <!--end::Menu Body--> <!--begin::Menu Footer-->--}}
                    <li class="user-footer">
                        <a href="{{ route('profile.show') }}" class="btn btn-default btn-flat">Profile</a>
                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end">Sign out</a></li>
                    <!--end::Menu Footer-->
                </ul>
            </li> <!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->
</nav> <!--end::Header-->
<script>
    // Using jQuery for AJAX (make sure jQuery is included in your project)
    $('.nav-item.dropdown').on('click', function () {
        $.ajax({
            url: '{{ route('notifications.markAsSeen') }}', // The route to mark notifications as seen
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                // Optionally, update the notification badge or UI here
                if (response === 'success'){
                    $('.navbar-badge').remove();
                }
            }
        });
    });
</script>
