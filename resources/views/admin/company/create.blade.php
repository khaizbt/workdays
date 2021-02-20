@extends('layouts.admin-master')

@section('title')
Create Company
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Company</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Company</a></div>
            <div class="breadcrumb-item">Create Company</div>
        </div>
    </div>
{{--
  <div class="section-header">
    <h1>Add User</h1>
  </div> --}}

    <div class="section-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('company.store')}}" enctype="multipart/form-data" method="POST">
                        <div class="card-header"><h4>Add a New Company</h4></div>
                        <div class="card-body">
                            @csrf
                            <div class="form-group">
                                <label for="title">Company Name</label>
                                <input type="text" class="form-control" name="name">
                                <span>Nama Perusahaan Harus Unix</span>
                            </div>
                            <div class="form-group">
                                <label for="admin">Admin</label>
                                <select name="id_user" id="select2" class="form-control">
                                    <option value="">Choose Admin</option>
                                    @foreach($user as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encrypt($value['id'])}}">{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" value="demo" name="logo" class="form-control">
                                <span><i>Logo Ini akan dipakai pada Slip gaji karyawan dan sebagainya</i></span>
                            </div>
                            <div class="form-group">
                                <label for="title">Company Email</label>
                                <input type="email"  class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label for="title">Company Phone</label>
                                <input type="number"  class="form-control" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="title">Company Address</label>
                                <textarea class="form-control" name="address" id="" cols="60" rows="30"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Number Leave</label>
                                <input type="number" min="1" class="form-control" name="number_leave">
                                <span>Jumlah Cuti Pada 1 Tahun</span>
                            </div>
                            <div class="form-group">
                                <label for="title">Maximum Leave</label>
                                <input type="number" min="1" class="form-control" name="maximum_leave">
                                <span>Jumlah Maksimum Cuti yang boleh diambil dalam satu waktu</span>
                            </div>
                            <div class="form-group">
                                <label for="title">Date Salary</label>
                                <input type="number" max="31" class="form-control" name="date_salary">
                                <span>Tanggal dimana gaji dibayarkan ke karyawan setiap bulan</span>
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
</section>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
    $('#select2').select2();
});
</script>
@endsection
