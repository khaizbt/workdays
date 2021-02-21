@extends('layouts.admin-master')

@section('title')
Create Offense
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create Offense</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('employee.index') }}">Offense</a></div>
            <div class="breadcrumb-item">Create </div>
        </div>
    </div>
    @include('notification')

    <div class="section-body">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <form action="{{route('ovense.store')}}" method="POST">
                        @csrf
                        <div class="card-header"><h4>Add a New Offense</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Offense Name <span class="required" aria-required="true"> * </span></label>
                                <input type="text" class="form-control" name="ovense_name">
                            </div>
                            <div class="form-group">
                                <label for="title">Employee <span class="required" aria-required="true"> * </span></label>

                                <select name="employee" id="select2" class="form-control">
                                    <option value="">Choose Employee</option>
                                    @foreach($employee as $key => $value)
                                        <option value="{{ Illuminate\Support\Facades\Crypt::encryptString($value['id']) }}">{{$value['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Pinalty Type <span class="required" aria-required="true"> * </span></label>
                                <select name="pinalty_type" class="form-control">
                                    <option value="">Chossee Type</option>
                                    <option value="Sanksi Lisan">Teguran</option>
                                    <option value="Denda">Potong Gaji</option>
                                    <option value="Penambahan Jam Kerja">Penambahan Jam Kerja</option>
                                    <option value="Skorsing">Skorsing</option>
                                    <option value="Pecat">Pemecatan</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group" id="punishment">
                                <label for="title">Punishment</label>
                                <input type="text" class="form-control input_mask_currency" placeholder="Punishment (Leave blank if not give punishment )" name="punishment">
                            </div>
                            <div class="form-group">
                                <label for="">Date <span class="required" aria-required="true"> * </span></label>
                                <input type="date" name="date"  class="form-control">

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
