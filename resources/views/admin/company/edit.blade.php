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
  <?php
    use Illuminate\Support\Facades\Crypt;
  ?>
  <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('company.update', $data['id'] ?? null)}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="card-header"><h4>Edit Company</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Company Name</label>
                                <input type="text" value="{{ $data['name'] ?? null }}" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                <label for="admin">Admin</label>

                                <select name="id_user" id="select2" class="form-control">
                                    <option value="">Choose Admin</option>
                                    @foreach($user as $key => $value)
                                    <option value="{{ Crypt::encrypt($value['id']) }}" {{($value['id'] == $data['id_user']) ? 'selected' : ''}}>{{$value['name']}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" value="demo" name="logo" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Hari Kerja</label>
                                <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[0]" value="1" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 1) ? 'checked' : "" }}
                                    @endforeach>
                                    <span class="selectgroup-button">Senin</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[1]" value="2" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 2) ? 'checked' : "" }}
                                    @endforeach>

                                    <span class="selectgroup-button">Selasa</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[2]" value="3" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 3) ? 'checked' : "" }}
                                    @endforeach>

                                    <span class="selectgroup-button">Rabu</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[3]" value="4" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 4) ? 'checked' : "" }}
                                    @endforeach>
                                    <span class="selectgroup-button">Kamis</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[4]" value="5" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 5) ? 'checked' : "" }}
                                    @endforeach>
                                    <span class="selectgroup-button">Jum'at</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[5]" value="6" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 6) ? 'checked' : "" }}
                                    @endforeach>
                                    <span class="selectgroup-button">Sabtu</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="value[6]" value="7" class="selectgroup-input"
                                    @foreach($data['work_days']  as $k => $v)
                                    {{ ($v['days'] == 7) ? 'checked' : "" }}
                                    @endforeach>
                                    <span class="selectgroup-button">Minggu</span>
                                </label>
                                </div>
                                <div class="control-label">Hari Libur</div>
                                    <label class="custom-switch mt-2">
                                    <input type="checkbox" name="work_holiday" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Diisi jika tanggal merah tetap masuk kerja</span>
                                </label>
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
