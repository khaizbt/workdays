@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>

  <div class="section-body">
      <div class="row">
          <div class="col-12">
              <div class="card-header">
                  <h4>Manage Companies</h4>
              </div>
              <div class="card-body">
                  <div class="table-responsive">
                      <table class="table table-striped" id="table-1">
                          <thead>
                              <tr>
                                  <th class="text-center">No</th>
                                  <th>Name</th>
                                  <th>Logo</th>
                                  <th>User</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
              </div>
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
            ajax: "{{ route('company.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                {data: 'name', name: 'name'},
                {data: 'logo', name: 'logo'},
                {data: 'id_user', name: 'id_user'},
                {data: 'action', name: 'action', searchable: false, orderable: false}
            ],
        });
</script>
@endsection
