@extends('layouts.admin-master')

@section('title')
Salary Cut
@endsection
@section('salary-cut', 'active')
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
        <h1>Manage Salary Cut</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>List Salary Cut
                            <!---->
                        </h4>
                        <div class="card-header-action"><a href="{{route('salarycut.create')}}"
                                class="btn btn-primary">Assign Salary Cut <i class="fas fa-plus"></i></a></div>
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
                                        <th width="25%">Ovense Name</th>
                                        <th width="25%">Employee Name</th>
                                        <th>Status</th>
                                        <th>Charge</th>
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
        "bFilter": true,
        "lengthChange": false,
        ajax: "{{ route('salarycut.data') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },
            {
                data: 'cuts_name',
                name: 'cuts_name'
            },
            {
                data: 'employee.name',
                name: 'employee.name'
            },

            {
                data: 'status_value',
                name: 'status_value'
            },
            {
                data: 'value',
                name: 'value'
            },
            {
                data: 'action',
                name: 'action',
                orderable: true
            }
        ],
    });

</script>
<script>


    function deleteSweet(id){
        let url = $('meta[name="url"]').attr('content')+'/salary-cut/delete/'+id;
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
                                            $(location).attr('href', "{{route('salarycut.index')}}");
                                        }
                                    });
                            }
                            if (response == 1) {
                                    swal("Success!", "Data has been deleted!", "success").then((result) => {
                                        if (result.value) {
                                            $(location).attr('href', "{{route('salarycut.index')}}");
                                        }
                                    $(location).attr('href', "{{route('salarycut.index')}}");
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
