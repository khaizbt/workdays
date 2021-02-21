<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji - {{ $employee['name'] }}</title>

    <style>
        .table-border, .th, .td {
            border: 1px solid black;
        }

        .table-border {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:0.85em; border-bottom:1px solid black">
        <tr>
            <td width="25" align="center"><img src="{{storage_path('app/'.$employee['company']['logo'])}}" alt="logo" width="100"></td>
            <td width="50" align="center">
                <h1>{{ $employee['company']['name'] }}</h1>
                <p>{{ $employee['company']['address'] }}</p>
                <p>{{ $employee['company']['email'] ." | ". $employee['company']['phone'] }}</p>
            </td>
            <td width="25" align="center">
                <div style="visibility: hidden">
                    <img src="{{storage_path('app/'.$employee['company']['logo'])}}" alt="logo" width="100">
                </div>
            </td>
        </tr>
    </table>
<br>
<div style="margin-left: 50px">
    <table style="font-family:Arial, Helvetica, sans-serif; font-size:0.85em">
        <tr>
            <td width="100px">Name</td>
            <td>: {{ $employee['name'] }}</td>
        </tr>
        <tr>
            <td width="100px">Position</td>
            <td>: {{ $employee['position'] }}</td>
        </tr>
        <tr>
            <td width="100px">Salary</td>
            <td>: Rp. {{ number_format($employee['salary'],0,',','.') }}</td>
        </tr>
        <tr>
            <td width="100px">Punishment</td>
            <td>: Rp. {{ number_format($employee['punishment_total'],0,',','.') }}</td>
        </tr>
        <tr>
            <td width="100px">Holiday Paid</td>
            <td>: Rp. {{ number_format($employee['holiday_paid_total'],0,',','.') }}</td>
        </tr>
        <tr>
            <td width="100px">Salary Cut</td>
            <td>: Rp. {{ number_format($employee['salary_cut_total'],0,',','.') }}</td>
        </tr>
        <tr>
            <td width="100px">Total Salary</td>
            <td>: Rp. {{ number_format($employee['salary_fix'],0,',','.') }}</td>
        </tr>
      </table>
      <br>
      <h3 style="font-family:Arial, Helvetica, sans-serif; font-size:0.85em">Ovense List</h3>
      <table class="table-border" style="width: 600px; font-family:Arial, Helvetica, sans-serif; font-size:0.85em">
        <thead align="center">
            <tr>
                <th class="td" scope="col">#</th>
                <th class="td" scope="col">Ovense Name</th>
                <th class="td" scope="col">Pinalty Type</th>
                <th class="td" scope="col">Date</th>
                <th class="td" scope="col">Punishment</th>
            </tr>
        </thead>
        <tbody align="center">
            @forelse($employee['ovense'] as $key_ovense => $value_ovense)
            <tr>
                <th class="th" scope="row">{{ $key_ovense+1 }}</th>
                    <td class="td">{{ $value_ovense['ovense_name'] }}</td>
                    <td class="td">{{ $value_ovense['pinalty_type'] }}</td>
                    <td class="td">{{ $value_ovense['date'] }}</td>
                    <td class="td">Rp.{{ number_format($value_ovense['punishment']) }}</td>
            </tr>
            @empty
            <tr>No Data</tr>
            @endforelse
        </tbody>
    </table>
    <br>
    <h3  style="font-family:Arial, Helvetica, sans-serif; font-size:0.85em">Leave Paid List</h3>
      <table class="table-border" style="width: 600px; font-family:Arial, Helvetica, sans-serif; font-size:0.85em">
        <thead align="center">
            <tr>
                <th class="td" scope="col">#</th>
                <th class="td" scope="col">Leave Name</th>
                <th class="td" scope="col">Date Start</th>
                <th class="td" scope="col">Date End</th>
                <th class="td" scope="col">Charge</th>
            </tr>
        </thead>
        <tbody align="center">
            @forelse($employee['holiday_paid'] as $key_paid => $value_paid)
            <tr>
                <th scope="row">{{ $key_paid+1 }}</th>
                    <td class="td">{{ $value_paid['leave_name'] }}</td>
                    <td class="td">{{ $value_paid['date_start'] }}</td>
                    <td class="td">{{ $value_paid['date_end'] }}</td>
                    <td class="td">Rp.{{ number_format($value_paid['charge']) }}</td>
            </tr>
            @empty
            <tr>No Data</tr>
            @endforelse
        </tbody>
    </table>



    <h3 style="font-family:Arial, Helvetica, sans-serif; font-size:0.85em">Salary Cut</h3>
      <table class="table-border" style="width: 600px; font-family:Arial, Helvetica, sans-serif; font-size:0.85em">
        <thead align="center">
            <tr>
                <th class="td" scope="col">#</th>
                <th class="td" scope="col">Salary Cut Name</th>
                <th class="td" scope="col">Notes</th>
                <th class="td" scope="col">Image</th>
                <th class="td" scope="col">Charge</th>
            </tr>
        </thead>
        <tbody align="center">
            @forelse($employee['salary_cut'] as $key_cut => $value_cut)
            <tr>
                <th class="td" scope="row">{{ $key_cut+1 }}</th>
                    <td class="td">{{ $value_cut['cuts_name'] }}</td>
                    <td class="td">{{ $value_cut['Notes'] }}</td>
                    <td><img src="{{storage_path('app/'.$value_cut['image'])}}" width="50"></td>
                    <td class="td">{{ $value_cut['value'] }}</td>
            </tr>
            @empty
            <tr>No Data</tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
