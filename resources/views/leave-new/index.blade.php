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
@section('leave', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Leave</h1>
    </div>
    @include('notification')
    <div class="section-body">
        @if(isset($holiday))
        <div class="row">
            @foreach($holiday as $key => $value)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card card-warning">
                    <div class="card-header">
                      <h4>{{ $value['leave_name'] }}</h4>
                    </div>
                    <div class="card-body">
                      <p>Employee : {{ $value['employee']['name'] }}</p>
                      <p>Date Start : {{ $value['date_start'] }}</p>
                      <p>Date End : {{ $value['date_end'] }}</p>
                    </div>
                    <div class="card-footer"><a href="javascript:;" class="btn btn-danger btn-sm" onClick='reject({{ $value['id'] }})'>Reject</a> <a href="#" class="btn btn-info btn-sm" onClick='accept({{ $value['id'] }})'>Accept</a></div>
                    {{-- <div class="row"></div>
                     &nbsp;  --}}
                  </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Leave
                            <!---->
                        </h4>
                        <div class="card-header-action"><a href="{{route('leave.create')}}"
                                class="btn btn-primary">Assign Leave <i class="fas fa-plus"></i></a></div>
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
                                        <th width="40%">Leave Name</th>
                                        <th>Type</th>
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
        ajax: "{{ route('leave.data') }}",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },
            {
                data: 'employee.name',
                name: 'employee.name'
            },
            {
                data: 'status_str',
                name: 'status_str'
            },
            {
                data: 'approved_str',
                name: 'approved_str'
            },
            {
                data: 'charge_str',
                name: 'charge_str'
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

    function reject(id) {
        let url = $('meta[name="url"]').attr('content')+'/leave/reject/'+id;
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Reject Reason',
                content: {
                element: 'input',
                attributes: {
                placeholder: 'type Your reason',
                type: 'text',
                },
                },
            }).then((data) => {
                $('#loading').show();
                $.ajax({
                    url : url,
                    type : "POST",
                    data : {
                        '_method' : "POST",
                        '_token' : csrf_token,
                        'reject_reason' : data,
                    },
                    cache: false,
                    success: function(response) {
                        swal(
                        "Sccess!",
                        "Leave Has Been Rejected!",
                        "success"
                        ).then((result) => {
                        if (result) {
                            $(location).attr('href', "{{route('leave.index')}}");
                        }
                    });
                    },
                    failure: function (response) {
                        swal(
                        "Internal Error",
                        "Oops, your reject has failed.", // had a missing comma
                        "error"
                        )
                    }
                })
                // swal('Hello, ' + data + '!');
            });
    }

    function accept(id) {
        let url = $('meta[name="url"]').attr('content')+'/leave/approve/'+id;
        let csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Charge(Leave Blank when not charged )',
                content: {
                element: 'input',
                attributes: {
                placeholder: ' Leave Charge ',
                type: 'number',
                },
                },
            }).then((data) => {
                $('#loading').show();
                $.ajax({
                    url : url,
                    type : "POST",
                    data : {
                        '_method' : "POST",
                        '_token' : csrf_token,
                        'charge' : data,
                    },
                    cache: false,
                    success: function(response) {
                        swal(
                        "Sccess!",
                        "Leave Has Been Rejected!",
                        "success"
                        ).then((result) => {
                        if (result) {
                            $(location).attr('href', "{{route('leave.index')}}");
                        }
                    });
                    },
                    failure: function (response) {
                        swal(
                        "Internal Error",
                        "Oops, your reject has failed.", // had a missing comma
                        "error"
                        )
                    }
                })
                // swal('Hello, ' + data + '!');
            });
    }
    function deleteSweet(id){
        let url = $('meta[name="url"]').attr('content')+'/leave/delete/'+id;
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
                                            $(location).attr('href', "{{route('leave.index')}}");
                                        }
                                    });
                            }
                            if (response == 1) {
                                    swal("Success!", "Data has been deleted!", "success").then((result) => {
                                        if (result.value) {
                                            $(location).attr('href', "{{route('leave.index')}}");
                                        }
                                    $(location).attr('href', "{{route('leave.index')}}");
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
