@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('style')
    <style>
        #table-1{
            font-size: 18px;
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
        <h1>Manage Companies</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Companies
                            <!---->
                        </h4>
                        <div class="card-header-action"><a href="{{route('company.create')}}"
                                class="btn btn-primary">Add <i class="fas fa-plus"></i></a></div>
                    </div>
                    <div class="card-body p-4">
                        @if ($is_data_empty)
                            <div class="text-center p-3 text-muted">
                                <h5>No Results</h5>
                                <p>Looks like you have not added any companies yet!</p>
                            </div>
                        @else
                            <table id="table-1" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th>Company Name</th>
                                        <th>Logo</th>
                                        <th>User</th>
                                        <th style="width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

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
<script>
    $.fn.dataTable.ext.errMode = 'none';
    $('#table-1').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        stateSave: true,
        "bFilter": false,
        "lengthChange": false,
        ajax: "{{ route('company.data') }}",
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
                data: 'logo',
                name: 'logo'
            },
            {
                data: 'id_user',
                name: 'id_user'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            }
        ],
    });

</script>
@endsection
