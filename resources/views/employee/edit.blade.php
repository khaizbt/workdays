@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Edit Companies</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Companies</a></div>
        <div class="breadcrumb-item">Edit Companies</div>
      </div>
  </div>

  <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('employee.update', $employee['id'])}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $user['name'] }}">
                            </div>
                            <div class="form-group">
                                <label for="title">Position</label>
                                <input type="text" class="form-control" name="position" value="{{  $employee['position'] }}">
                            </div>
                            <div class="form-group">
                                <label for="title">Status</label>
                                <input type="text" class="form-control" name="status" value="{{ $employee['status'] }}">
                            </div>
                            <div class="form-group">
                                <label for="title">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user['email'] }}">
                            </div>
                            <div class="form-group">
                                <label for="title">Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label for="title">Salary</label>
                                <input type="number" class="form-control" name="salary" value="{{ $employee['salary'] }}">
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
