{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Clinic Appointment System</title>
    
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
                <h4 class="text-center">Admin Panel</h4>
                <hr>
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.doctors.index') }}" class="nav-link text-white {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                            <i class="fas fa-user-md me-2"></i> Doctors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.patients.index') }}" class="nav-link text-white {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.specializations.index') }}" class="nav-link text-white {{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}">
                            <i class="fas fa-stethoscope me-2"></i> Specializations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.schedules.index') }}" class="nav-link text-white {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt me-2"></i> Schedules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.appointments.index') }}" class="nav-link text-white {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link text-white {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i> Reports
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
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- Check if profile route exists --}}
                            @if(Route::has('profile.edit'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-cog me-2"></i> Profile Settings
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            {{-- Alternative profile link if profile.edit doesn't exist --}}
                            @if(!Route::has('profile.edit'))
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); alert('Profile feature coming soon!');">
                                        <i class="fas fa-user-cog me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 py-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for some features) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle sidebar
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            const wrapper = document.getElementById("wrapper");
            wrapper.classList.toggle("toggled");
            
            // Optional: Save state in localStorage
            const isToggled = wrapper.classList.contains("toggled");
            localStorage.setItem("sidebarToggled", isToggled);
        });

        // Restore sidebar state from localStorage
        document.addEventListener("DOMContentLoaded", function() {
            const wrapper = document.getElementById("wrapper");
            const isToggled = localStorage.getItem("sidebarToggled") === "true";
            
            if (isToggled) {
                wrapper.classList.add("toggled");
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Confirm before logout (optional)
        document.getElementById('logout-form')?.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    </script>

    <!-- Custom CSS for sidebar toggle -->
    <style>
        #wrapper {
            overflow-x: hidden;
        }
        
        #wrapper.toggled #sidebar-wrapper {
            margin-left: -250px;
        }
        
        @media (min-width: 768px) {
            #sidebar-wrapper {
                transition: margin 0.25s ease-out;
            }
        }
        
        .nav-link {
            border-radius: 0;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .nav-link.active {
            background-color: #0d6efd !important;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .alert {
            border-left: 4px solid;
            animation: slideIn 0.3s ease-out;
        }
        
        .alert-success {
            border-left-color: #198754;
        }
        
        .alert-danger {
            border-left-color: #dc3545;
        }
        
        .alert-warning {
            border-left-color: #ffc107;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -250px;
                position: fixed;
                z-index: 1000;
                height: 100vh;
                transition: margin 0.25s ease-out;
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                width: 100%;
            }
        }
    </style>
    
    @stack('scripts')
</body>
</html>