@extends('layouts.admin-master')

@section('title')
Manage Admin
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Admin</h1>
  </div>
  <div class="section-body">
      <users-component></users-component>
  </div>
</section>
@endsection
