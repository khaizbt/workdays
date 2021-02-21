@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Companies</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Companies</a></div>
        <div class="breadcrumb-item">Edit Companies</div>
      </div>
  </div>
  <?php
    use Illuminate\Support\Facades\Crypt;
  ?>
    @include('notification')

  <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('update.company')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="card-header"><h4>Edit Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Company Name</label>
                                <input type="text" value="{{ $data['name'] ?? null }}" class="form-control" name="name">
                            </div>

                            <div class="form-group">

                                <label for="logo">Company Logo</label><br>
                                <img width="300px" src="{{ route('get.file', str_replace('/', '+', $data['logo'])) }}" alt="">
                                <input type="file" value="demo" name="logo" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="title">Company Email</label>
                                <input type="email" value="{{ $data['email'] ?? null }}" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label for="title">Company Phone</label>
                                <input type="number" value="{{ $data['phone'] ?? null }}" class="form-control" name="phone">
                            </div>
                            <div class="form-group">
                                <label for="title">Company Address</label>
                                <textarea class="form-control" name="address" id="" cols="60" rows="30">{{ $data['address'] }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="title">Number Leave</label>
                                <input type="number" min="1" class="form-control" value="{{ $data['number_leave'] }}" name="number_leave">
                                <span>Jumlah Cuti Pada 1 Tahun</span>
                            </div>
                            <div class="form-group">
                                <label for="title">Maximum Leave</label>
                                <input type="number" min="1" class="form-control" value="{{ $data['maximum_leave'] }}" name="maximum_leave">
                                <span>Jumlah Maksimum Cuti yang boleh diambil dalam satu waktu</span>
                            </div>
                            <div class="form-group">
                                <label for="title">Date Salary</label>
                                <input type="number" max="31" class="form-control" value={{ $data['date_salary'] }} name="date_salary">
                                <span>Tanggal dimana gaji dibayarkan ke karyawan setiap bulan</span>
                            </div>


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
@endsection
