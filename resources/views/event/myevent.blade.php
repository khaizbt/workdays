@extends('layouts.admin-master')

@section('title')
My Event
@endsection

@section('style')
    <style>
        #table-1{
            font-size: 14px;
            padding: 10px;
        }

        td{
            padding: 8px;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }
    </style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>My Event</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Event
                            <!---->
                        </h4>
                        <div class="card-header-action"></div>
                    </div>
                    <div class="card-body p-4">
                        @if ($is_data_empty)
                            <div class="text-center p-3 text-muted">
                                <h5>No Results</h5>
                                <p>Looks like you have not added any Event yet!</p>
                            </div>
                        @else
                            <table class="table table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th width="40%">Event Name</th>
                                        <th>Note</th>
                                        <th>Time</th>
                                        <th>Place</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event as $key => $value)
                                    <tr>
                                        <th>{{ $key+1 }}</th>
                                        <th>{{ $value['event_name'] }}</th>
                                        <th>{{ $value['note'] }}</th>
                                        <th>{{ $value['time'] }}</th>
                                        <th>{{ $value['place'] }}</th>

                                        {{-- <th>{{ $value['created_at'] }}</th> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')

@endsection