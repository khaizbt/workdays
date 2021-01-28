@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Edit Companies</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('company.index') }}">Companies</a></div>
        <div class="breadcrumb-item">Edit Companies</div>
      </div>
  </div>

  <div class="section-body">
      <h2 class="section-body">Forms</h2>
      <p class="section-lead">
          Isilah Data Perusahaan yang baik dan benar
      </p>
      <div class="row">
        <form action="{{route('company.update', $data['id'])}}" enctype="multipart/form-data" method="POST">
            @csrf
          <div class="col-8">
              <div class="card">
                  <div class="card-body">
                      <div class="form-group">
                          <label for="title">Company Name</label>
                          <input type="text" value="{{ $data['name'] }}" class="form-control" name="name">
                      </div>
                      <div class="form-group">
                          <label for="admin">Admin</label>
                          @foreach($user as $key => $value)
                          <select name="id_user" id="select2" class="form-control select2">
                            <option value="">Choose Admin</option>
                              <option value="{{$value['id']}}" {{($value['id'] == $data['id_user']) ? 'selected' : ''}}>{{$value['name']}}</option>
                          </select>
                          @endforeach
                      </div>
                      <div class="form-group">
                          <label for="logo">Logo</label>
                          <input type="file" value="demo" name="logo" class="form-control">
                      </div>

                      <div class="form-group">
                        <label class="form-label">Hari Kerja</label>
                        <div class="selectgroup selectgroup-pills">
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="HTML" class="selectgroup-input" checked="">
                            <span class="selectgroup-button">Senin</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="CSS" class="selectgroup-input">
                            <span class="selectgroup-button">Selasa</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="PHP" class="selectgroup-input">
                            <span class="selectgroup-button">Rabu</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="JavaScript" class="selectgroup-input">
                            <span class="selectgroup-button">Kamis</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                            <span class="selectgroup-button">Jum'at</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                            <span class="selectgroup-button">Sabtu</span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="checkbox" name="value" value="C++" class="selectgroup-input">
                            <span class="selectgroup-button">Minggu</span>
                          </label>
                        </div>
                            <div class="control-label">Hari Libur</div>
                            <label class="custom-switch mt-2">
                              <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input">
                              <span class="custom-switch-indicator"></span>
                              <span class="custom-switch-description">Diisi jika tanggal merah tetap masuk kerja</span>
                            </label>
                      </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                    <button class="btn btn-secondary" type="reset">Reset</button>
                  </div>
              </div>
          </div>
        </form>
      </div>
  </div>
</section>
@endsection
@section('scripts')
@endsection
