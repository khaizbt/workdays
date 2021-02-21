@extends('layouts.admin-master')

@section('title')
Create Emplotee
@endsection
@section('employee', 'active');
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Employee</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('employee.index') }}">Employeee</a></div>
            <div class="breadcrumb-item">Create Employee</div>
        </div>
    </div>
    @include('notification')

    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('employee.store')}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Employee</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Name <span class="required" aria-required="true"> * </span></label>
                                <input type="text" placeholder="Employee Name" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="title">Position <span class="required" aria-required="true"> * </span></label>
                                <input type="text" placeholder="Employee Position" class="form-control" name="position" required>
                            </div>

                            <div class="form-group">
                                <label for="title">Email <span class="required" aria-required="true"> * </span></label>
                                <input type="email" placeholder="Employee Email"  class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label for="title">Password <span class="required" aria-required="true"> * </span></label>
                                <input type="password" placeholder="Employee Password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label for="title">Salary <span class="required" aria-required="true"> * </span></label>
                                <input type="text" placeholder="Employee Salary" class="form-control input_mask_currency" name="salary">
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
<script src="{{ asset('assets/modules/jinputmask.js') }}"></script>

<script src="{{ asset('assets/modules/inputmask.js') }}"></script>
<script>
$(".input_mask_currency").inputmask({
    rightAlign: false,
    prefix : 'Rp ',
    radixPoint: ',',
    groupSeparator: ".",
    alias: "numeric",
    autoGroup: true,
    digits: 0,
    min: 0
});
</script>
@endsection
