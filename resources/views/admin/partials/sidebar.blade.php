<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="index.html">WD</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{ Request::route()->getName() == 'admin.dashboard' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fa fa-columns"></i> <span>Dashboard</span></a></li>

        @role('Super')
        <li class="menu-header">Users</li>
        <li class="{{ Request::route()->getName() == 'admin.users' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.users') }}"><i class="fa fa-users"></i> <span>Users</span></a></li>
        @endrole

        <li class="menu-header">Company</li>
        <li class="{{ Request::route()->getName() == 'holiday.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('holiday.index') }}"><i class="fa fa-calendar-alt"></i> <span>Calendar</span></a></li>

        @role("Super")
        <li class="{{ Request::route()->getName() == 'company.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('company.index') }}"><i class="fa fa-globe"></i> <span>Manage Company</span></a></li>

        @endrole
        @role("Admin")
        <li class="{{ Request::route()->getName() == 'employee.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('employee.index') }}"><i class="fa fa-users"></i> <span>Manage Employee</span></a></li>
        <li class="{{ Request::route()->getName() == 'leave.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('leave.index') }}"><i class="fa fa-calendar-times"></i> <span>Manage Leave</span></a></li>
        <li class="@yield('pre_setting_list')" ><a class="nav-link" href="{{ route('salary.index') }}"><i class="fa fa-calculator"></i> <span>Manage Salary</span></a></li>
        {{-- <li class="{{ Request::route()->getName() == 'company.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('company.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Company</span></a></li> --}}
        <li class="{{ Request::route()->getName() == 'ovense.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('ovense.index') }}"><i class="fa fa-money-check-alt"></i> <span>Manage Offense</span></a></li>
        <li class="{{ Request::route()->getName() == 'salarycut.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('salarycut.index') }}"><i class="fa fa-cut"></i> <span>Manage Salary Cut</span></a></li>
        <li class="{{ Request::route()->getName() == 'event.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('event.index') }}"><i class="fa fa-birthday-cake"></i> <span>Manage Event</span></a></li>
        @endrole
        @role("User")
<li class="{{ Request::route()->getName() == 'salary.show' ? ' active' : '' }}"><a class="nav-link" href="{{ route('salary.show', Illuminate\Support\Facades\Crypt::encrypt(Auth::id()) ) }}"><i class="fa fa-calculator"></i> <span>My Salary</span></a></li>

        <li class="{{ Request::route()->getName() == 'ovense.my' ? ' active' : '' }}"><a class="nav-link" href="{{ route('ovense.my') }}"><i class="fa fa-money-check-alt"></i> <span>My Offense</span></a></li>
        <li class="{{ Request::route()->getName() == 'salarycut.my' ? ' active' : '' }}"><a class="nav-link" href="{{ route('salarycut.my') }}"><i class="fa fa-cut"></i> <span>My Salary Cut</span></a></li>
        <li class="{{ Request::route()->getName() == 'event.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('event.index') }}"><i class="fa fa-birthday-cake"></i> <span>Manage Event</span></a></li>

        @endrole
        </ul>
</aside>
