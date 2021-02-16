<table>
    <thead>
        <b>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Position</th>
            <th>Gross Salary</th>
            <th>Punishment</th>
            <th>Holiday Paid</th>
            <th>Salary Cut</th>
            <th>Salary Fixed</th>

        </tr>
        </b>
    </thead>
    <tbody>
        @foreach($employee as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $value['name'] }}</td>
            <td>{{ $value['position'] }}</td>
            <td>{{ $value['salary'] }}</td>
            <td>{{ $value['punishment_total'] }}</td>
            <td>{{ $value['holiday_paid_total'] ?? 0 }}</td>
            <td>{{ $value['salary_cut_total'] }}</td>
            <td>{{ $value['salary_fix'] }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
