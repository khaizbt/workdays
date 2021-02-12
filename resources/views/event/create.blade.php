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
                    <form action="{{route('event.store')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Event</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Event Name</label>
                                <input type="text" class="form-control" name="event_name">
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="note" id="" class="form-control" cols="30" rows="50"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Date And Time</label>
                                <input type="datetime-local" class="form-control" name="time" id="">
                            </div>
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="text"  class="form-control"name="place" id="">
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
