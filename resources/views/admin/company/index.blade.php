@extends('layouts.admin-master')

@section('title')
Dashboard
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
        <h1>Manage Companies</h1>
    </div>
    @include('notification')
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
                                        <th width="40%">Company Name</th>
                                        <th>Admin</th>
                                        <th>Number Leave</th>
                                        <th>Date Salary</th>
                                        <th style="width: 10%;">Action</th>
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
                data: 'user.name',
                name: 'user.name'
            },
            {
                data: 'number_leave',
                name: 'number_leave'
            },
            {
                data: 'date_salary',
                name: 'date_salary'
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
<script>


    function deleteSweet(id){
        let url = $('meta[name="url"]').attr('content')+'/company/delete/'+id;
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this imaginary file!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
                }).then((result) => {
                    console.log(result)

                if (result) {
                    $('#loading').show();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': csrf_token
                        },
                        success: function (response) {
                            $('#loading').hide();
                            console.log(response)
                            if (response != 1) {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                }).then((result) => {
                                        if (result.value) {
                                            $(location).attr('href', "{{route('company.index')}}");
                                        }
                                    });
                            }
                            if (response == 1) {
                                    swal("Success!", "Data has been deleted!", "success").then((result) => {
                                        if (result.value) {
                                            $(location).attr('href', "{{route('company.index')}}");
                                        }
                                    $(location).attr('href', "{{route('company.index')}}");
                                });
                            }
                        },
                        error: function (xhr) {
                            $('#loading').hide();
                            swal("Oops....", "Something went wrong!", "error");
                        }
                    });
                }
            });
	};
</script>
@endsection
