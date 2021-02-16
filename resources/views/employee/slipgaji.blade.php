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
    <table width="100%">
        <tr>
            <td width="25" align="center"><img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="logo" width="100"></td>
            <td width="50" align="center">
                <h1>{{ $employee['company']['name'] }}</h1>
                <p>{{ $employee['company']['address'] }}</p>
                <p>{{ $employee['company']['email'] ." | ". $employee['company']['phone'] }}</p>
            </td>
            <td width="25" align="center"><img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="logo" width="100"></td>
        </tr>
    </table>
<hr>
<br>
<div style="margin-left: 50px">
    <table>
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
            <td>: Rp.{{ number_format($employee['salary']) }}</td>
        </tr>
        <tr>
            <td width="100px">Punishment</td>
            <td>: Rp.{{ number_format($employee['punishment_total']) }}</td>
        </tr>
        <tr>
            <td width="100px">Holiday Paid</td>
            <td>: Rp.{{ number_format($employee['holiday_paid_total']) }}</td>
        </tr>
        <tr>
            <td width="100px">Salary Cut</td>
            <td>: Rp.{{ number_format($employee['salary_cut_total']) }}</td>
        </tr>
        <tr>
            <td width="100px">Total Salary</td>
            <td>: Rp.{{ number_format($employee['salary_fix']) }}</td>
        </tr>
      </table>
      <br>
      <h3>Ovense List</h3>
      <table class="table-border" style="width: 600px">
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
    <h3>Leave Paid List</h3>
      <table class="table-border" style="width: 600px">
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



    <h3>Salary Cut</h3>
      <table class="table-border" style="width: 600px">
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
                    <td><img width="100px" src="{{ route('get.file', str_replace("/", "+", $value_cut['image'])) }}" alt=""></td>
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
