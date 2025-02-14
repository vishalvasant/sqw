<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHREEJI QUARRY WORKS</title>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed text-sm">
@php
    $usr = Auth::guard('web')->user();
@endphp
    <div class="wrapper">
        <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        @php
            $notifications = auth()->user()->unreadNotifications;
        @endphp
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
             <!-- Task Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-bell"></i>
                    @if($notifications->count() > 0)
                        <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
                    <div class="dropdown-divider"></div>
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->data['url'] }}" class="dropdown-item text-wrap">
                            <i class="fas fa-tasks mr-2"></i> {{ $notification->data['message'] }}
                            <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
                    <a href="{{ route('notifications.markAllAsRead') }}" class="dropdown-item dropdown-footer">Mark all as read</a>
                </div>
            </li>
            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user"></i>
                    <span class="ml-2">{{ Auth::user()->name ?? 'User' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar') <!-- Include sidebar partial -->


        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    
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
    <!-- Select2 -->
    <script src="{{asset('adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('adminlte/plugins/chart.js/Chart.min.js')}}"></script>
    @if(session('success'))
        <script>
            $(document).ready(function() {
                $(document).Toasts('create', {
                    class: 'bg-success', // Green success toast
                    title: 'Success',
                    subtitle: 'Now',
                    body: '{{ session("success") }}',
                    autohide: true,
                    delay: 3000 // Toast will disappear after 3 seconds
                });
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Roles",
                allowClear: true
            });
        });
    </script>
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


    @isset($months)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var poChartElement = document.getElementById('poChart');
            var prChartElement = document.getElementById('prChart');

            if (poChartElement) {
                var ctx1 = poChartElement.getContext('2d');
                var poChart = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Purchase Orders',
                            data: @json($poCounts),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }

            if (prChartElement) {
                var ctx2 = prChartElement.getContext('2d');
                var prChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Purchase Requests',
                            data: @json($prCounts),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            }
        });
    </script>
    @endisset
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Error',
                    body: "{{ $errors->first() }}",
                    autohide: true,
                    delay: 5000
                });
            });
        </script>
    @endif
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
