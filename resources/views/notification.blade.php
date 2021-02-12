@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <div class="alert-title">Success</div>
        {{ $message }}
    </div>
	@endif

	@if ($message = Session::get('error'))
	<div class="alert alert-danger">
        <div class="alert-title">Danger</div>
        {{ $message }}
      </div>
	@endif
