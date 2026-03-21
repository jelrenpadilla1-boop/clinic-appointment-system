{{-- resources/views/layouts/doctor.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor - Clinic Appointment System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
            <div class="p-3">
                <h4 class="text-center">Doctor Panel</h4>
                <hr>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('doctor.dashboard') }}" class="nav-link text-white {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-dashboard me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('doctor.appointments.index') }}" class="nav-link text-white {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('doctor.schedule') }}" class="nav-link text-white {{ request()->routeIs('doctor.schedule') ? 'active' : '' }}">
                            <i class="fas fa-clock me-2"></i> My Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('doctor.patients.index') }}" class="nav-link text-white {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> My Patients
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" style="flex: 1;">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown ms-auto">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Dr. {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    
    @stack('scripts')
</body>
</html>