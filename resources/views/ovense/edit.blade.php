@extends('layouts.admin-master')

@section('title')
Create Ovense
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Leave</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('employee.index') }}">Ovense</a></div>
            <div class="breadcrumb-item">Update </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('ovense.update', Illuminate\Support\Facades\Crypt::encrypt($data['id']))}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Ovense Name</label>
                                <input type="text" class="form-control" value={{ $data['ovense_name'] }} name="ovense_name">
                            </div>
                            <div class="form-group">
                                <label for="title">Employee</label>
                                <select name="employee_id" id="select2" class="form-control">
                                    <option value="">Choose Employee</option>
                                    @foreach($employee as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encrypt($value['id']) }}" {{ ($value['id'] == $data['employee_id']) ? 'selected' : "" }}>{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Pinalty Type</label>
                                <select name="pinalty_type" class="form-control">
                                    <option value="">Chossee Type</option>
                                    <option value="Sanksi Lisan" {{ ($data['pinalty_type'] == "Sanksi Lisan") ? "selected" : "" }}>Teguran</option>
                                    <option value="Denda" {{ ($data['pinalty_type'] == "Denda") ? "selected" : "" }}>Potong Gaji</option>
                                    <option value="Penambahan Jam Kerja" {{ ($data['pinalty_type'] == "Penambahan Jam Kerja") ? "selected" : "" }}>Penambahan Jam Kerja</option>
                                    <option value="Skorsing"  {{ ($data['pinalty_type'] == "Skorsing") ? "selected" : "" }}>Skorsing</option>
                                    <option value="Pecat"  {{ ($data['pinalty_type'] == "Pecat") ? "selected" : "" }}>Pemecatan</option>
                                    <option value="Other"  {{ ($data['pinalty_type'] == "Other") ? "selected" : "" }}>Other</option>
                                </select>
                            </div>
                            <div class="form-group" id="punishment">
                                <label for="title">Punishment</label>
                                <input type="number" value="{{ $data['punishment'] }}" class="form-control" name="punishment">
                            </div>
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" name="date" value="{{ $data['date'] }}"  class="form-control">

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
