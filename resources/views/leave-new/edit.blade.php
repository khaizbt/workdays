@extends('layouts.admin-master')

@section('title')
Update Leave
@endsection
@section('leave', 'active')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manage Leave</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('leave.index') }}">Leave</a></div>
            <div class="breadcrumb-item">Update </div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('leave.update', Illuminate\Support\Facades\Crypt::encryptString($data['id']))}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Edit Leave</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Leave Name</label>
                                <input type="text" placeholder="Leave Name" class="form-control" value="{{ $data['leave_name'] }}" name="leave_name">
                            </div>
                            <div class="form-group">
                                <label for="title">Employee</label>
                                <select name="employee" id="select2" class="form-control">
                                    <option value="">Choose Employee</option>
                                    @foreach($employee as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encryptString($value['id']) }}" {{ ($value['id'] == $data['employee_id']) ? "selected" : "" }}>{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Leave Type</label>
                                <select name="status" class="form-control">
                                    <option value="">Chossee Type</option>
                                    <option value="1" {{ ($data['status'] == 1) ? 'selected' : '' }}>Cuti</option>
                                    <option value="2" {{ ($data['status'] == 2) ? 'selected' : '' }}>Sakit</option>
                                    <option value="3" {{ ($data['status'] == 3) ? 'selected' : '' }}>Alpha</option>
                                    <option value="4" {{ ($data['status'] == 4) ? 'selected' : '' }}>Izin</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Charge</label>
                                <input type="text" placeholder="Charge(Leave Blank if not give charge)" class="form-control input_mask_currency" value="{{ $data['charge'] }}" name="charge">
                            </div>
                            <div class="form-group">
                                <label for="">Date</label>
                                <div class="input-group">
                                    <input type="date" name="date_start" value="{{ $data['date_start'] }}"  class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="la la-ellipsis-h"></i>To</span>
                                </div>
                                <input type="date" class="form-control" value="{{ $data['date_end'] }}" name="date_end">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title">Status</label>
                                <select name="is_approved" class="form-control">
                                    <option value="">Chossee Status</option>
                                    <option value="1" {{ ($data['is_approved'] == 1) ? 'selected' : '' }}>Approved</option>
                                    <option value="0" {{ ($data['is_approved'] == 0) ? 'selected' : '' }}>Rejected</option>
                                    <option value="2" {{ ($data['is_approved'] == 2) ? 'selected' : '' }}>Pending</option>

                                </select>
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
    </div>
</section>
@endsection
@section('scripts')
<script src="{{ asset('assets/modules/jinputmask.js') }}"></script>

<script src="{{ asset('assets/modules/inputmask.js') }}"></script>
<script>
$(".input_mask_currency").inputmask({
    rightAlign: false,
    prefix : 'Rp ',
    radixPoint: ',',
    groupSeparator: ".",
    alias: "numeric",
    autoGroup: true,
    digits: 0,
    min: 0
});

$(document).ready(function(){
    $('#select2').select2();
})
</script>
@endsection
