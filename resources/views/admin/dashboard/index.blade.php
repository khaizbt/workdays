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
          @role('Admin')
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-statistic-2">
              <div class="card-stats">
                <div class="card-stats-title">Overview {{ date('F') }}

                </div>
                <div class="card-stats-items">
                  <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $data['count_working'] }}</div>
                    <div class="card-stats-item-label">Hari Kerja</div>
                  </div>
                  <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $data['count_employee'] }}</div>
                    <div class="card-stats-item-label">Karyawan</div>
                  </div>
                  <div class="card-stats-item">
                    <div class="card-stats-item-count">{{ $data['count_leave'] }}</div>
                    <div class="card-stats-item-label">Cuti</div>
                  </div>
                </div>
              </div>
              <div class="card-icon shadow-primary bg-primary">
                <i class="fas fa-archive"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4>Tanggal Gajian</h4>
                </div>
                <div class="card-body" style="margin-top: 10px">
                  <h6>{{ $data['gajian'] }}</h6>
                </div>
              </div>
            </div>
          </div>
          @endrole
          @role('User')
          <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title">Overview {{ date('F') }}

                  </div>
                  <div class="card-stats-items">
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">{{ $data['count_working'] }}</div>
                      <div class="card-stats-item-label">Hari Kerja</div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">{{ $data['count_leave'] }}</div>
                      <div class="card-stats-item-label">Cuti Tersisa</div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">{{ $data['ovense'] }}</div>
                      <div class="card-stats-item-label">Pelanggaran</div>
                    </div>
                  </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-archive"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Tanggal Gajian</h4>
                  </div>
                  <div class="card-body" style="margin-top: 10px">
                    <h6>{{ $data['gajian'] }}</h6>
                  </div>
                </div>
              </div>
            </div>
            @endrole
  </div>
</section>
@endsection
