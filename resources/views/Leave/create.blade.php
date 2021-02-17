@extends('layouts.admin-master')

@section('title')
Create Leave
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Leave</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('employee.index') }}">Leave</a></div>
            <div class="breadcrumb-item">Create </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('leave.store')}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Leave Name</label>
                                <input type="text" class="form-control" name="leave_name">
                            </div>
                            <div class="form-group">
                                <label for="title">Employee</label>
                                <select name="employee" id="select2" class="form-control">
                                    <option value="">Choose Employee</option>
                                    @foreach($employee as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encryptString($value['id']) }}">{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Leave Type</label>
                                <select name="status" class="form-control">
                                    <option value="">Chossee Type</option>
                                    <option value="1">Cuti</option>
                                    <option value="2">Sakit</option>
                                    <option value="3">Alpha</option>
                                    <option value="4">Izin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Charge</label>
                                <input type="number" class="form-control" name="charge">
                            </div>
                            <div class="form-group">
                                <label for="">Date</label>
                                <div class="input-group">

                                <input type="date" name="date_start"  class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i>To</span>
                                </div>
                                <input type="date" class="form-control" name="date_end">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title">Status</label>
                                <select name="is_approved" class="form-control">
                                    <option value="">Chossee Status</option>
                                    <option value="1">Approved</option>
                                    <option value="0">No Approved</option>
                                    <option value="2">Pending</option>

                                </select>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $( function() {
    $( "#date_range" ).daterangepicker({
        "minDate": moment(),
        "maxSpan": {
        "days": 2
    },
}, function(start, end, label) {
  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
});
    });
  </script>
{{-- <script src="{{ asset("assets/js/page/datepicker.js") }}"></script> --}}
@endsection
