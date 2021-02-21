@extends('layouts.admin-master')

@section('title')
My Offense
@endsection
@section('ovense', 'active')
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
        <h1>My Offense</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Offense
                            <!---->
                        </h4>
                        <div class="card-header-action"></div>
                    </div>
                    <div class="card-body p-4">
                        @if ($is_data_empty)
                            <div class="text-center p-3 text-muted">
                                <h5>No Results</h5>
                                <p>Looks like you't have any Offense yet!</p>
                            </div>
                        @else
                            <table class="table table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th width="20%">Offense Name</th>
                                        <th>Pinalty Type</th>
                                        <th>Date</th>
                                        <th>Punishment</th>

                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['ovense'] as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value['ovense_name'] }}</td>
                                        <td>{{ $value['pinalty_type'] }}</td>
                                        <td>{{ $value['date'] }}</td>
                                        <td>Rp.{{ number_format($value['punishment']) }}</td>

                                        <td>{{ $value['created_at'] }}</td>
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
