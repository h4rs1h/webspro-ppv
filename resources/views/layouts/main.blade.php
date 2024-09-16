<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Media Prima Jaringan') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('startmin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('startmin/css/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Timeline CSS -->
    {{--  <link href="{{ asset('startmin/css/timeline.css') }}" rel="stylesheet">  --}}

    <!-- Custom CSS -->
    <link href="{{ asset('startmin/css/startmin.css') }}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    {{--  <link href="{{ asset('startmin/css/morris.css') }}" rel="stylesheet">  --}}

    <!-- Custom Fonts -->
    <link href="{{ asset('startmin/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="{{ asset('startmin/css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('startmin/css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">

    {{--  <link href="/css/pricing.css" rel="stylesheet">  --}}

{{-- Trix Editoe --}}

<link rel="stylesheet" type="text/css" href="{{ asset('css/trix.css') }}">
<script type="text/javascript" src="{{ asset('js/trix.js') }}"></script>

<style>
    trix-toolbar [data-trix-button-group="file-tools"]{
        display: none;
    }
</style>


</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        @include('layouts.navbar')

        <!-- Sidebar -->
        @include('layouts.sidebar')
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        @yield('content')
    </div>

</div>

<!-- jQuery -->
<script src="{{ asset('startmin/js/jquery.min.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('startmin/js/bootstrap.min.js') }}"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('startmin/js/metisMenu.min.js') }}"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('startmin/js/startmin.js') }}"></script>

<!-- DataTables JavaScript -->
<script src="{{ asset('startmin/js/dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('startmin/js/dataTables/dataTables.bootstrap.min.js') }}"></script>

	<!-- DataTable -->
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js" type="text/javascript"></script>

{{--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  --}}

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
	<script>
    const btn_export = document.querySelector("#export_btn");

    btn_export.addEventListener("change", function () {

        var i = btn_export.value;
        var table = $('#dataTables-example').DataTable();
        if (i == 1) {
            table.button('.buttons-csv').trigger();
        } else if (i == 2) {
            table.button('.buttons-excel').trigger();
        } else if (i == 3) {
            table.button('.buttons-pdf').trigger();
        } else if (i == 4) {
            table.button('.buttons-print').trigger();
        }
    });

</script>
	
        <script>
            $(document).ready(function() {
                $('#dataTables-example').DataTable({
                        responsive: true,
					 buttons: [
                                    'csv', 'excel', 'pdf', 'print'
                                 ],
                    columnDefs: [{
                        orderable: true,
                        targets: -1
                    }]
                });
            });
        </script>
</body>
</html>
