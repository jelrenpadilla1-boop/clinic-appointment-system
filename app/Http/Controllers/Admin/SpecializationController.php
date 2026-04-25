<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index(Request $Request)
    {
        // Base query for the table/cards (with doctors_count)
        $query = Specialization::withCount('doctors');

        // 1. Search by name
        if ($search = $Request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 2. Sorting
        switch ($Request->get('sort')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'doctors_high':
                $query->orderBy('doctors_count', 'desc');
                break;
            default: // 'newest'
                $query->latest();
        }

        // 3. Paginate (10 per page)
        $specializations = $query->paginate(10);
        $specializations->appends($Request->only(['search', 'sort']));

        // ---- STATS for ALL specializations (ignoring pagination & filters) ----
        $totalSpecializations = Specialization::count();
        $totalDoctors = Specialization::withCount('doctors')->get()->sum('doctors_count');
        $avgDoctorsPerSpec = $totalSpecializations > 0 ? round($totalDoctors / $totalSpecializations, 1) : 0;
        $latestSpecialization = Specialization::latest()->first();

        return view('admin.specializations.index', compact(
            'specializations',
            'totalSpecializations',
            'totalDoctors',
            'avgDoctorsPerSpec',
            'latestSpecialization'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:specializations',
            'description' => 'nullable|string',
        ]);

        Specialization::create($request->all());

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization created successfully.');
    }

    public function update(Request $request, Specialization $specialization)
    {
        $request->validate([
            'name' => 'required|string|unique:specializations,name,' . $specialization->id,
            'description' => 'nullable|string',
        ]);

        $specialization->update($request->all());

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization updated successfully.');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();

        return redirect()->route('admin.specializations.index')
            ->with('success', 'Specialization deleted successfully.');
    }
}