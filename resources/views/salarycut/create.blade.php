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
                    <form action="{{route('salarycut.store')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Salary Cut Name</label>
                                <input type="text" class="form-control" name="cuts_name">
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Employee</label>
                                <select name="employee_id" id="select2" class="form-control">
                                    <option value="">Choose Employee</option>
                                    @foreach($employee as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encryptString($value['id']) }}">{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" name="image" id="">
                            </div>
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="number"  class="form-control"name="value" id="">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Chossee Status</option>
                                    <option value="0">Dipotong 1x</option>
                                    <option value="1">Dipotong Perbulan</option>
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
