<form class="form-inline mr-auto" action="{{ route('admin.users') }}">
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
  </ul>
  <div class="search-element">
    {{-- <input class="form-control" value="{{ Request::get('query') }}" name="query" type="search" placeholder="Search" aria-label="Search" data-width="250">
    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
    <div class="search-backdrop"></div> --}}

  </div>
</form>
<ul class="navbar-nav navbar-right">
  <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg{{ Auth::user()->unreadNotifications->count() ? ' beep' : '' }}"><i class="far fa-bell"></i></a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right">
      <div class="dropdown-header">Notifications
        <div class="float-right">
          <a href="{{ route('notif.read.all') }}">Mark All As Read</a>
        </div>
      </div>
      <div class="dropdown-list-content dropdown-list-icons">
        @if(Auth::user()->unreadNotifications->count())
        @foreach(Auth::user()->unreadNotifications as $key => $value)
          <a href="{{ route('notif.read',Illuminate\Support\Facades\Crypt::encrypt($value['id'])) }}" class="dropdown-item dropdown-item-unread">
          <div class="dropdown-item-icon bg-primary text-white">
            <i class="fas fa-code"></i>
          </div>
          <div class="dropdown-item-desc">
            {{ $value['data']['message'] }}
            <div class="time text-primary">{{ $value['created_at'] }}</div>
          </div>
        </a>
        @endforeach
        @else
        <p class="text-muted p-2 text-center">No notifications found!</p>
        @endif
    </div>
  </li>
  <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
    <img alt="image" src="{{ Auth::user()->avatarlink }}" class="rounded-circle mr-1">
    <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
    <div class="dropdown-menu dropdown-menu-right">
      <div class="dropdown-title">Welcome, {{ Auth::user()->name }}</div>
      <a href="{{ Auth::user()->profilelink }}" class="dropdown-item has-icon">
        <i class="far fa-user"></i> Profile Settings
      </a>
      @role("Admin")
      <a href="{{ route('edit.company') }}" class="dropdown-item has-icon">
        <i class="far fa-id-card"></i> Company Settings
      </a>
      @endrole
      <div class="dropdown-divider"></div>
      <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>

    </div>
  </li>
</ul>
