@extends('layouts.admin-master')

@section('title')
Submit Leave
@endsection
@section('leave', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Leave</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('leave.my') }}">Leave</a></div>
            <div class="breadcrumb-item">Submit </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('submit.leave')}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Submit Your Leave</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Leave Name</label>
                                <input type="text" placeholder="Leave Name" class="form-control" name="leave_name">
                            </div>

                            <div class="form-group">
                                <label for="title">Leave Type</label>
                                <select name="status" class="form-control">
                                    <option value="">Chossee Type</option>
                                    <option value="1">Cuti</option>
                                    <option value="2">Sakit</option>
                                    <option value="4">Izin</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Date</label>
                                <div class="input-group">                                <input type="date" name="date_start"  class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i>To</span>
                                </div>
                                <input type="date" class="form-control" name="date_end">
                            </div>

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
{{-- <script src="{{ asset("assets/js/page/datepicker.js") }}"></script> --}}
@endsection