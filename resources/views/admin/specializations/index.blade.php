{{-- resources/views/admin/specializations/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Header with stats + action row --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 mb-0">Specializations</h1>
            <p class="text-muted mt-1">Manage medical disciplines and their associated doctors</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addSpecializationModal">
            <i class="fas fa-plus me-2"></i>New Specialization
        </button>
    </div>

    {{-- Stats row (now using REAL totals, not paginated data) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 bg-gradient-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Specializations</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalSpecializations }}</h2>
                        </div>
                        <i class="fas fa-tags fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-gradient-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Doctors</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalDoctors }}</h2>
                        </div>
                        <i class="fas fa-user-md fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-gradient-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Avg. Doctors / Spec</h6>
                            <h2 class="mb-0 fw-bold">{{ $avgDoctorsPerSpec }}</h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-gradient-secondary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Last added</h6>
                            <h6 class="mb-0 fw-bold">
                                {{ $latestSpecialization ? $latestSpecialization->created_at->diffForHumans() : '—' }}
                            </h6>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + filters row --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.specializations.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Search by name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cardiology, Neurology..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Sort by</label>
                    <select name="sort" class="form-select">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest first</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest first</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A–Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z–A)</option>
                        <option value="doctors_high" {{ request('sort') == 'doctors_high' ? 'selected' : '' }}>Most doctors</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Apply filters</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Card grid --}}
    @if($specializations->count())
        <div class="row g-4">
            @foreach($specializations as $specialization)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift transition-all">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-primary-soft rounded-circle p-2 text-primary" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-stethoscope fa-2x"></i>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editSpecializationModal{{ $specialization->id }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.specializations.destroy', $specialization) }}" method="POST" onsubmit="return confirm('Permanently delete this specialization? Linked doctors will be affected.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash-alt me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <h5 class="card-title fw-bold mb-1">{{ $specialization->name }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($specialization->description, 80) ?: 'No description provided.' }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-md text-primary me-1"></i>
                                    <span class="fw-semibold">{{ $specialization->doctors_count }}</span> {{-- FIXED: use preloaded count --}}
                                    <span class="text-muted ms-1">doctors</span>
                                </div>
                                <small class="text-muted" title="{{ $specialization->created_at->format('M d, Y') }}">
                                    <i class="far fa-calendar-alt me-1"></i>{{ $specialization->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $specializations->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-folder-open fa-4x text-muted opacity-25"></i>
            </div>
            <h5>No specializations found</h5>
            <p class="text-muted">Try adjusting your search or create a new specialization.</p>
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addSpecializationModal">
                <i class="fas fa-plus me-2"></i>Add your first specialization
            </button>
        </div>
    @endif
</div>

{{-- ADD MODAL (clean, centered) --}}
<div class="modal fade" id="addSpecializationModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">➕ Create new specialization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.specializations.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3" name="name" placeholder="e.g., Dermatology" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description (optional)</label>
                        <textarea class="form-control rounded-3" name="description" rows="3" placeholder="Brief description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODALS (one per specialization) --}}
@foreach($specializations as $specialization)
<div class="modal fade" id="editSpecializationModal{{ $specialization->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">✏️ Edit specialization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.specializations.update', $specialization) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" class="form-control form-control-lg rounded-3" name="name" value="{{ old('name', $specialization->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control rounded-3" name="description" rows="3">{{ old('description', $specialization->description) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Custom CSS (unchanged) --}}
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #198754, #157347);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0, #0aa2c9);
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d, #5c636a);
    }
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.1);
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,.1) !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .card {
        border-radius: 1.25rem !important;
    }
    .rounded-4 {
        border-radius: 1.25rem;
    }
    .form-control-lg, .btn-lg {
        border-radius: 0.75rem;
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>
@endsection