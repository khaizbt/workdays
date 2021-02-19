@extends('layouts.admin-master')

@section('title')
Salary
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
@section('salary', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Salary</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List Salary
                            <!---->
                        </h4>
                        <div class="card-header-action">

                            <a href="{{ route('excel.salary') }}" class="btn btn-success">Export to Excel <i class="fa fa-file" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="card-body p-4">
                        {{-- @if ($is_data_empty)
                            <div class="text-center p-3 text-muted">
                                <h5>No Results</h5>
                                <p>Looks like you have not added any companies yet!</p>
                            </div>
                        @else --}}
                            <table id="table-1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th width="40%">Employee Name</th>
                                        <th>Base Salary</th>
                                        <th>Punishment</th>
                                        <th>Holiday Paid</th>
                                        <th>Salary Cut</th>
                                        <th>Salary Fix</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        {{-- @endif --}}
                    </div>
                </div>
                <!---->
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    $('#table-1').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        stateSave: true,
        "bFilter": true,
        "lengthChange": false,
        ajax: "{{ route('salary.data') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'salary',
                name: 'salary'
            },
            {
                data: 'punishment_total',
                name: 'punishment_total'
            },
            {
                data: 'holiday_paid_total',
                name: 'holiday_paid_total'
            },
            {
                data: 'salary_cut_total',
                name: 'salary_cut_total'
            },
            {
                data: 'salary_fix',
                name: 'salary_fix'
            },
            {
                data: 'action',
                name: 'action',
                orderable: true
            }
        ],
    });

</script>
@endsection
