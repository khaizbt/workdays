@extends('layouts.admin-master')

@section('title')
My Salary Cut
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
        <h1>My Salary Cut</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Saalry Cut
                            <!---->
                        </h4>

                    <div class="card-body p-4">
                        @if ($is_data_empty)
                            <div class="text-center p-3 text-muted">
                                <h5>No Results</h5>
                                <p>Looks like you have not added any companies yet!</p>
                            </div>
                        @else
                            <table class="table table-striped" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th width="20%">Cuts Name</th>
                                        <th>Notes</th>
                                        <th>Image</th>
                                        <th>Value</th>
                                        <th style="width: 10%;">Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['salary_cut'] as $key => $value)
                                    <tr>
                                        <th>{{ $key+1 }}</th>
                                        <th>{{ $value['cuts_name'] }}</th>
                                        <th>{{ $value['notes'] }}</th>
                                        <th><img width="100px" src="{{ route("get.file", str_replace("/", "+", $value['image'])) }}" alt=""> </th>
                                        <th>Rp.{{ number_format($value['value']) }}</th>
                                        <th>{{ ($value['status'] == 1) ? "Dipotong Perbulan" : "Dipotong 1x" }}</th>
                                        <th>{{ $value['created_at'] }}</th>
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
