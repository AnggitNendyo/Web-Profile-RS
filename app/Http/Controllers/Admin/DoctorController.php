<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\Degree;
use Inertia\Inertia;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with('specialties')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('front_title', 'ilike', "%{$search}%")
                    ->orWhere('back_title', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%")
                    ->orWhereHas('specialties', fn($q) => $q->where('name', 'ilike', "%{$search}%"));
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Doctors/Index', [
            'doctors' => $doctors,
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Doctors/Form', [
            'specialties' => Specialty::all(),
            'degrees' => Degree::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'front_title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255', // Jabatan tambahan
            'start_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'specialties' => 'required|array|min:1',
            'specialties.*' => 'exists:specialties,id',
            'is_active' => 'required|boolean',
            'education' => 'nullable|array',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $doctor = Doctor::create([
            'front_title' => $validated['front_title'],
            'name' => $validated['name'],
            'back_title' => $validated['back_title'],
            'title' => $validated['title'] ?? '',
            'start_year' => $validated['start_year'],
            'is_active' => $validated['is_active'],
            'education' => $validated['education'] ?? [],
        ]);

        // Sync Specialties
        $doctor->specialties()->sync($validated['specialties']);

        // Insert Schedules
        if (!empty($validated['schedules'])) {
            $doctor->schedules()->createMany($validated['schedules']);
        }

        if ($request->hasFile('photo')) {
            $doctor->addMediaFromRequest('photo')->toMediaCollection('doctor_photos');
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit(Doctor $doctor)
    {
        // Load relationships needed for the form
        $doctor->load('specialties', 'schedules');
        
        return Inertia::render('Admin/Doctors/Form', [
            'doctor' => $doctor,
            'specialties' => Specialty::all(),
            'degrees' => Degree::all()
        ]);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'front_title' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'start_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'specialties' => 'required|array|min:1',
            'specialties.*' => 'exists:specialties,id',
            'is_active' => 'required|boolean',
            'education' => 'nullable|array',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|string',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $doctor->update([
            'front_title' => $validated['front_title'],
            'name' => $validated['name'],
            'back_title' => $validated['back_title'],
            'title' => $validated['title'] ?? '',
            'start_year' => $validated['start_year'],
            'is_active' => $validated['is_active'],
            'education' => $validated['education'] ?? $doctor->education,
        ]);

        // Sync Specialties
        $doctor->specialties()->sync($validated['specialties']);

        // Sync Schedules
        $doctor->schedules()->delete(); // Remove old schedules
        if (!empty($validated['schedules'])) {
            $doctor->schedules()->createMany($validated['schedules']);
        }

        if ($request->hasFile('photo')) {
            $doctor->clearMediaCollection('doctor_photos');
            $doctor->addMediaFromRequest('photo')->toMediaCollection('doctor_photos');
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil dihapus.');
    }
}
