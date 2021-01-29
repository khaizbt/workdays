@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Companies</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Companies</a></div>
        <div class="breadcrumb-item">Create Companies</div>
      </div>
  </div>

  <div class="section-body">
      <h2 class="section-body">Forms</h2>
      <p class="section-lead">
          Isilah Data Perusahaan yang baik dan benar
      </p>
      @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

      <div class="row">
        <form action="{{route('employee.store')}}" method="POST">
            @csrf
            <div class="col-8">
                <div class="card">

                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label for="title">Position</label>
                            <input type="text" class="form-control" name="position">
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <input type="text" class="form-control" name="status" value="active">
                        </div>
                        <div class="form-group">
                            <label for="title">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for="title">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label for="title">Salary</label>
                            <input type="number" class="form-control" name="salary">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                        <button class="btn btn-secondary" type="reset">Reset</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
  </div>
</section>
@endsection
@section('scripts')
@endsection
