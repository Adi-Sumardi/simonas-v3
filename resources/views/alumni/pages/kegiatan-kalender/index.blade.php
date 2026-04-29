@extends('alumni.layouts.master')

@section('title') Kegiatan @endsection

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Schedule Tracker</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
@endsection

@section('css')

    <link href="{{URL::asset('libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{URL::asset('libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" />

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    @component('common-components.breadcrumb')
        @slot('title') Kegiatan @endslot
    @endcomponent

    <div class="container mt-5">
        {{-- For Search --}}
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search events">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                    <button id="exportButton" class="btn btn-success">Export Calendar</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="calendar" style="width: 100%;height:100vh"></div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Tambah Acara Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventTitle">Judul Kegiatan:</label>
                        <input type="text" class="form-control" id="eventTitle">
                    </div>
                    <div class="form-group">
                        <label for="dateRange">Tanggal Kegiatan:</label>
                        <input type="date" class="form-control" id="dateRange">
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Asrama:</label>
                        <select class="form-control" id="eventColor">
                            <option value="">- Pilih Asrama -</option>
                            <option value="red">ASGJ</option>
                            <option value="blue">ASG</option>
                            <option value="green">AWS</option>
                            <option value="yellow">ASPURI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    

@endsection

@section('script')

    <script src="{{URL::asset('/libs/select2/select2.min.js')}}"></script>
    <script src="{{URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{URL::asset('/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{URL::asset('/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
    <script src="{{URL::asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>

    <!-- form advanced init -->
    <script src="{{URL::asset('/js/pages/form-advanced.init.js')}}"></script>
        
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <script>
        // Inisialisasi datepicker untuk dateRange input
        $(function() {
            $("#dateRange").datepicker({
                numberOfMonths: 2, // Anda dapat menyesuaikan jumlah bulan yang ingin ditampilkan
                onSelect: function(selectedDate) {
                    var option = this.id == "startDate" ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
                    dates.not(this).datepicker("option", option, date);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                initialView: 'dayGridMonth',
                timeZone: 'UTC',
                events: '/events',
                editable: true,
                dateClick: function(info) {
                    $('#eventModal').modal('show');
                    $('#eventTitle').val(''); // Clear the title input
                }
            });

            calendar.render();

            document.getElementById('searchButton').addEventListener('click', function() {
                var searchKeywords = document.getElementById('searchInput').value.toLowerCase();
                filterAndDisplayEvents(searchKeywords);
            });

            document.getElementById('saveEvent').addEventListener('click', function() {
                var title = $('#eventTitle').val();
                if (title) {
                    calendar.addEvent({
                        title: title,
                        start: info.date,
                        allDay: true,
                    });
                    $('#eventModal').modal('hide');
                }
            });

            function filterAndDisplayEvents(searchKeywords) {
                $.ajax({
                    method: 'GET',
                    url: `/events/search?title=${searchKeywords}`,
                    success: function(response) {
                        calendar.removeAllEvents();
                        calendar.addEventSource(response);
                    },
                    error: function(error) {
                        console.error('Error searching events:', error);
                    }
                });
            }

            document.getElementById('exportButton').addEventListener('click', function() {
                var events = calendar.getEvents().map(function(event) {
                    return {
                        title: event.title,
                        start: event.start ? event.start.toISOString() : null,
                        end: event.end ? event.end.toISOString() : null,
                        color: event.backgroundColor,
                    };
                });
            
                var wb = XLSX.utils.book_new();
            
                var ws = XLSX.utils.json_to_sheet(events);
            
                XLSX.utils.book_append_sheet(wb, ws, 'Events');
            
                var arrayBuffer = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'array'
                });
            
                var blob = new Blob([arrayBuffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
            
                var downloadLink = document.createElement('a');
                downloadLink.href = URL.createObjectURL(blob);
                downloadLink.download = 'events.xlsx';
                downloadLink.click();
            });
        });
    </script>

@endsection
