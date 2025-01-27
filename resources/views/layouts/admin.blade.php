<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHREEJI QUARRY WORKS</title>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar') <!-- Include sidebar partial -->


        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">SHREEJI QUARRY WORKS</a>.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <script>
    $(function () {
        $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        });
    });
    </script>

<!-- Script to calculate avarage in difference of value of odometer -->
    <script>
        $().ready(function() {
            var vt = $('#vehicle_type').val();
            if(vt == 'KMS'){
                $('#readings').on('input', function() {
                    var readings = $(this).val();
                    var odometer = $('#odometer').val();
                    var diff = readings - odometer;
                    $('#kmsrun').val(diff);
                });
    
                $('#fuel_amount').on('input', function() {
                    var readings = $(this).val();
                    var km = $('#kmsrun').val();
                    var diff =  km / readings;
                    $('#cost_per_liter').val(diff);
                });
            }else if(vt == 'FIXED'){
                $('#fuel_amount').on('input', function() {
                    var fuel = $(this).val();
                    var hr = $('#hours_used').val();
                    var diff =  fuel/hr;
                    $('#cost_per_liter').val(diff);
                });
            }else if(vt == 'TRIP'){
                $('#fuel_amount').on('input', function() {
                    var fuel = $(this).val();
                    var tr = $('#trip').val();
                    var diff =  fuel/tr;
                    $('#cost_per_liter').val(diff);
                });
            }
        });
        
    </script>
</body>
</html>
