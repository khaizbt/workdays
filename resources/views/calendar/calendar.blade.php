@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Calendar</h1>
  </div>
<div class="card">
  <div class="section-body">
      <div class="card-body">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
        <div id="calendar"></div>
        <div id="gabut"></div>
      </div>
  </div>
</div>
</section>
@endsection
@section('scripts')
@parent
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function () {

            // page is now ready, initialize the calendar...
            events={!! json_encode($events) !!};
            var calendar = $('#calendar').fullCalendar({
                events: events,
                editable: true,
                displayEventTime: false,
                selectable: true,

                select:  (start, end, allDay) => {
                    // $(this).fireModal({body: 'Modal body text goes here.', title:"Ini Modal",  center: true});
                  var date = moment(start, 'DD.MM.YYYY').format('YYYY-MM-DD');
                  var url = '{{ route("holiday.count.working", ":val") }}';
                  url = url.replace(':val', date),
                  $.ajax({
                    url : url,
                    type: "GET",
                    success :(data) => {

                    //   console.log(this);
                    alert('Hari kerja kantor dipotong libur adalah '+data+ ' hari')
                    //   modal(fireModal({body: 'Modal body text goes here.', title:"Ini Modal",  center: true}));
                    }
                  });

                }
            })
        });
</script>
@stop
