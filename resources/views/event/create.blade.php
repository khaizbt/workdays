@extends('layouts.admin-master')

@section('title')
Create Event
@endsection
@section('event', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Event</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('event.index') }}">Leave</a></div>
            <div class="breadcrumb-item">Create Event</div>
        </div>
    </div>
    @include('notification')
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
                                <input type="text" class="form-control" name="event_name" required>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="note" id="" class="form-control" cols="30" rows="50" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Date And Time</label>
                                <input type="datetime-local" class="form-control" name="time" id="" required>
                            </div>
                            <div class="form-group">
                                <label for="value">Place</label>
                                <input type="text"  class="form-control"name="place" id="" required>
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
