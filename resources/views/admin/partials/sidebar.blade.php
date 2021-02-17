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
        <li class="{{ Request::route()->getName() == 'holiday.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('holiday.index') }}"><i class="far fa-calendar-alt"></i> <span>Calendar</span></a></li>

        @role("Super")
        <li class="{{ Request::route()->getName() == 'company.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('company.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Company</span></a></li>

        @endrole
        @role("Admin")
        <li class="{{ Request::route()->getName() == 'employee.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('employee.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Employee</span></a></li>
        <li class="{{ Request::route()->getName() == 'leave.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('leave.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Leave</span></a></li>
        <li class="{{ Request::route()->getName() == 'salary.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('salary.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Salary</span></a></li>
        <li class="{{ Request::route()->getName() == 'company.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('company.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Company</span></a></li>
        <li class="{{ Request::route()->getName() == 'ovense.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('ovense.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Offense</span></a></li>
        <li class="{{ Request::route()->getName() == 'salarycut.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('salarycut.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Salary Cut</span></a></li>
        <li class="{{ Request::route()->getName() == 'event.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('event.index') }}"><i class="far fa-calendar-alt"></i> <span>Manage Event</span></a></li>

        @endrole
        </ul>
</aside>
//TODO List Ovense, Salary Cut di Calendar
// TODO Show Detail Company, Edit Company by Admin
