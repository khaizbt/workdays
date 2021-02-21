@extends('layouts.admin-master')

@section('title')
Create Admin
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Admin</h1>
  </div>
  @include('notification')
  <div class="section-body">
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h4>Add a New Admin</h4>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                <div class="card-body">
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="text" name="name" placeholder="Name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="email" placeholder="Email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="password" name="password" placeholder="Password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Confirm Password</label>
                        <div class="col-sm-12 col-md-7">
                            <input type="password" name="password_confirmation" placeholder="Password Confirmation" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <button v-bind:disabled="loading" @click="addUser" class="btn btn-primary"><span v-if="loading">Adding</span><span v-else>Add</span></button> --}}
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
  </div>
</section>
@endsection
