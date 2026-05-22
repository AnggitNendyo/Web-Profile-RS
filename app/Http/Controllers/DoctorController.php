<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\SpecialtyResource;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['specialties', 'schedules'])->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('specialty')) {
            $query->whereHas('specialties', function($q) use ($request) {
                $q->where('slug', $request->specialty);
            });
        }

        if ($request->filled('day')) {
            $query->whereHas('schedules', function($q) use ($request) {
                $q->where('day_of_week', $request->day);
            });
        }

        $doctors = $query->paginate(12)->withQueryString();
        $specialties = Specialty::withCount('doctors')->get();

        return Inertia::render('Public/Doctors/Index', [
            'doctors' => DoctorResource::collection($doctors),
            'specialties' => SpecialtyResource::collection($specialties),
            'filters' => $request->only(['search', 'specialty', 'day'])
        ]);
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['specialties', 'schedules']);
        
        $relatedDoctors = Doctor::with(['specialties', 'schedules'])
            ->whereHas('specialties', function($q) use ($doctor) {
                $q->whereIn('specialties.id', $doctor->specialties->pluck('id'));
            })
            ->where('id', '!=', $doctor->id)
            ->where('is_active', true)
            ->take(3)
            ->get();

        return Inertia::render('Public/Doctors/Show', [
            'doctor' => new DoctorResource($doctor),
            'relatedDoctors' => DoctorResource::collection($relatedDoctors),
        ]);
    }
}
