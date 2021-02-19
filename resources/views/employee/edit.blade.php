@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection
@section('employee', 'active')
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Employee</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Employee</a></div>
        <div class="breadcrumb-item">Edit Employee</div>
      </div>
  </div>

  <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('employee.update', $employee['id'])}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Employee</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $user['name'] }}" required>
                            </div>
                            <div class="form-group">
                                <label for="title">Position</label>
                                <input type="text" class="form-control" name="position" value="{{  $employee['position'] }}" required>
                            </div>

                            <div class="form-group">
                                <label for="title">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" required>
                            </div>
                            <div class="form-group">
                                <label for="title">Password</label>
                                <input type="password" placeholder="Password (Leave Blank if not change Password)" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label for="title">Salary</label>
                                <input type="number" class="form-control" name="salary" value="{{ $employee['salary'] }}" required>
                            </div>
                        </div>
                            <div class="card-footer">
                                <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                <button class="btn btn-secondary" type="reset">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
          </div>
      </div>
  </div>
</section>
@endsection
@section('scripts')
@endsection
