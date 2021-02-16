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
        <h1>Salary</h1>
    </div>
    @include('notification')
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Detail Salary</h4>
                        <div class="card-header-action">
                            <a href="#" class="btn btn-primary">
                                Download Slip Gaji
                            </a>
                        </div>
                        </div>
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Employee Name</td>
                                            <td>: {{ $employee['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Position</td>
                                            <td>: {{ $employee['position'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Salary</td>
                                            <td>:  Rp.{{ $employee['salary'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Punishment</td>
                                            <td>: Rp.{{ $employee['punishment_total'] }}</td>
                                        </tr>
                                        <tr><td>Holiday Paid</td>
                                        <td>: Rp.{{ $employee['holiday_paid_total'] }}</td></tr>
                                        <tr>
                                            <td>Salary Cut </td>
                                            <td>Rp. {{ $employee['salary_cut_total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fix Salary</td>
                                            <td>: Rp.{{ $employee['salary_fix'] }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="section-title">Ovense List</div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Ovense Name</th>
                                                                <th scope="col">Pinalty Type</th>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Punishment</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($employee['ovense'] as $key_ovense => $value_ovense)
                                                            <tr>
                                                                <th scope="row">{{ $key_ovense+1 }}</th>
                                                                    <td>{{ $value_ovense['ovense_name'] }}</td>
                                                                    <td>{{ $value_ovense['pinalty_type'] }}</td>
                                                                    <td>{{ $value_ovense['date'] }}</td>
                                                                    <td>{{ $value_ovense['punishment'] }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>No Data</tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="section-title">Holiday Paid List</div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Leave Name</th>
                                                                <th scope="col">Date Start</th>
                                                                <th scope="col">Date End</th>
                                                                <th scope="col">Charge</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($employee['holiday_paid'] as $key_paid => $value_paid)
                                                            <tr>
                                                                <th scope="row">{{ $key_paid+1 }}</th>
                                                                    <td>{{ $value_paid['leave_name'] }}</td>
                                                                    <td>{{ $value_paid['date_start'] }}</td>
                                                                    <td>{{ $value_paid['date_end'] }}</td>
                                                                    <td>{{ $value_paid['charge'] }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>No Data</tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="section-title">Salary Cut</div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Salary Cut Name</th>
                                                                <th scope="col">Date Start</th>
                                                                <th scope="col">Date End</th>
                                                                <th scope="col">Charge</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($employee['salary_cut'] as $key_cut => $value_cut)
                                                            <tr>
                                                                <th scope="row">{{ $key_cut+1 }}</th>
                                                                    <td>{{ $value_cut['cuts_name'] }}</td>
                                                                    <td>{{ $value_cut['Notes'] }}</td>
                                                                    <td><img width="100px" src="{{ route('get.file', str_replace("/", "+", $value_cut['image'])) }}" alt=""></td>
                                                                    <td>{{ $value_cut['value'] }}</td>
                                                            </tr>
                                                            @empty
                                                            <tr>No Data</tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            </div>

                                        </div>

                            </div>
                        </div>
                        </div>
                    </div>
                <!---->
            </div>
        </div>
    </div>
</section>
@endsection

