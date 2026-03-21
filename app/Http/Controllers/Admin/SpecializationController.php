<?php
// app/Http/Controllers/Admin/SpecializationController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::paginate(10);
        return view('admin.specializations.index', compact('specializations'));
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