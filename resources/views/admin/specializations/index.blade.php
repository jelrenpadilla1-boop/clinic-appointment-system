{{-- resources/views/admin/specializations/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Specializations</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecializationModal">
            <i class="fas fa-plus"></i> Add Specialization
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Doctors Count</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($specializations as $specialization)
                    <tr>
                        <td>{{ $specialization->id }}</td>
                        <td>{{ $specialization->name }}</td>
                        <td>{{ Str::limit($specialization->description, 50) }}</td>
                        <td>{{ $specialization->doctors->count() }}</td>
                        <td>{{ $specialization->created_at->format('M d, Y') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editSpecializationModal{{ $specialization->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.specializations.destroy', $specialization) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will affect all doctors in this specialization.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $specializations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Specialization Modal -->
<div class="modal fade" id="addSpecializationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.specializations.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Specialization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Specialization Modals -->
@foreach($specializations as $specialization)
<div class="modal fade" id="editSpecializationModal{{ $specialization->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.specializations.update', $specialization) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Specialization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $specialization->id }}" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name{{ $specialization->id }}" 
                               name="name" value="{{ $specialization->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $specialization->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="description{{ $specialization->id }}" 
                                  name="description" rows="3">{{ $specialization->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection